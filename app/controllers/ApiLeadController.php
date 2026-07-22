<?php
/**
 * PropertyRubix — API Lead Controller
 */

class ApiLeadController extends ApiBaseController {

    /**
     * POST /api/v1/leads
     * Process external lead intake with validation, deduplication, and auto-routing.
     */
    public function store(array $params = []): void {
        $this->authenticate('leads:write');
        $pdo = db();

        // Get payload from JSON request or POST parameters
        $input = $this->getJsonInput();
        if (empty($input)) {
            $input = $_POST;
        }

        $name      = trim($input['name'] ?? '');
        $phone     = trim($input['phone'] ?? '');
        $email     = trim($input['email'] ?? '');
        $projectId = isset($input['project_id']) ? (int)$input['project_id'] : null;
        $cityId    = isset($input['city_id']) ? (int)$input['city_id'] : null;
        $message   = trim($input['message'] ?? '');
        $source    = trim($input['source'] ?? 'contact_form');

        // Validation
        $errors = [];
        if (!$name) {
            $errors['name'] = 'Name field is required.';
        }
        if (!$phone) {
            $errors['phone'] = 'Phone number is required.';
        }
        if ($email && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email address.';
        }

        // Validate CPT associations if provided
        if ($projectId) {
            $projCheck = $pdo->prepare("SELECT id, city_id FROM projects WHERE id = ? LIMIT 1");
            $projCheck->execute([$projectId]);
            $project = $projCheck->fetch(PDO::FETCH_ASSOC);
            if (!$project) {
                $errors['project_id'] = 'Associated project does not exist.';
            } else {
                // Auto-fill city_id if not explicitly provided
                if (!$cityId) {
                    $cityId = (int)$project['city_id'];
                }
            }
        }

        if ($cityId && !isset($errors['project_id'])) {
            $cityCheck = $pdo->prepare("SELECT id FROM cities WHERE id = ? LIMIT 1");
            $cityCheck->execute([$cityId]);
            if (!$cityCheck->fetch()) {
                $errors['city_id'] = 'Associated city does not exist.';
            }
        }

        if (!empty($errors)) {
            $this->apiError('Validation failed.', 422, $errors);
        }

        // Deduplication: Check for duplicates within last 24 hours
        $dupStmt = $pdo->prepare("
            SELECT id, created_at 
            FROM leads 
            WHERE phone = ? AND (project_id = ? OR (project_id IS NULL AND ? IS NULL))
              AND created_at >= NOW() - INTERVAL 1 DAY
            ORDER BY id DESC 
            LIMIT 1
        ");
        $dupStmt->execute([$phone, $projectId, $projectId]);
        $duplicate = $dupStmt->fetch(PDO::FETCH_ASSOC);

        if ($duplicate) {
            // Log attempt but do not create new entry
            $pdo->prepare("
                INSERT INTO crm_sync_logs (direction, payload, status, response) 
                VALUES ('inbound', ?, 'success', ?)
            ")->execute([
                json_encode($input),
                "Duplicate lead detected. Merged with existing lead ID: " . $duplicate['id']
            ]);

            $this->apiSuccess([
                'lead_id'   => (int)$duplicate['id'],
                'duplicate' => true,
                'message'   => 'Duplicate request detected within 24 hours. Data merged.'
            ], [], 200);
            return;
        }

        // Auto-Routing Resolution (Assigned User)
        $assignedUserId = null;
        if ($projectId || $cityId) {
            $routeStmt = $pdo->prepare("
                SELECT assign_to_user_id 
                FROM lead_routing_rules 
                WHERE is_active = 1 
                  AND (project_id = ? OR (project_id IS NULL AND city_id = ?))
                ORDER BY priority DESC, id ASC 
                LIMIT 1
            ");
            $routeStmt->execute([$projectId, $cityId]);
            $assignedUserId = $routeStmt->fetchColumn() ?: null;
        }

        if ($assignedUserId === null) {
            // Default to first active superadmin/admin if no match
            $adminStmt = $pdo->query("SELECT id FROM users WHERE status='active' AND role IN ('super_admin', 'admin') LIMIT 1");
            $assignedUserId = $adminStmt->fetchColumn() ?: null;
        }

        // Insert Lead
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? null;
        $insertQuery = "
            INSERT INTO leads (name, email, phone, source, project_id, city_id, message, assigned_to_user_id, ip_address, status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 'new')
        ";
        $insertStmt = $pdo->prepare($insertQuery);
        $insertStmt->execute([
            $name,
            $email ?: null,
            $phone,
            $source,
            $projectId ?: null,
            $cityId ?: null,
            $message ?: null,
            $assignedUserId,
            $ipAddress
        ]);

        $leadId = (int)$pdo->lastInsertId();

        // Write Audit Action
        $pdo->prepare("
            INSERT INTO audit_log (action, entity, entity_id, new_value, ip_address) 
            VALUES ('CREATE', 'leads', ?, ?, ?)
        ")->execute([
            $leadId,
            json_encode(['name' => $name, 'phone' => $phone, 'assigned_to_user_id' => $assignedUserId]),
            $ipAddress
        ]);

        $this->apiSuccess([
            'lead_id'   => $leadId,
            'duplicate' => false,
            'assigned'  => $assignedUserId !== null,
            'message'   => 'Lead created and assigned successfully.'
        ], [], 21);
    }
}
