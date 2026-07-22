<?php
/**
 * PropertyRubix — API Syndication Feed Controller
 */

class ApiFeedController extends ApiBaseController {

    /**
     * GET /api/v1/feeds/trovit
     * Render compliant Trovit XML property feed.
     */
    public function trovitXml(array $params = []): void {
        $this->authenticate('listings:read');
        $pdo = db();

        // Fetch properties (active only)
        $stmt = $pdo->query("
            SELECT p.*,
                   pr.name AS project_name, 
                   b.name AS builder_name, 
                   c.name AS city_name, 
                   s.name AS state_name
            FROM properties p
            LEFT JOIN projects pr ON pr.id = p.project_id
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN states s ON s.id = c.state_id
            WHERE p.status = 'Active'
            ORDER BY p.id DESC
        ");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/xml; charset=utf-8');

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><trovit></trovit>');
        
        foreach ($properties as $p) {
            $ad = $xml->addChild('ad');
            $ad->addChild('id', $p['id']);
            
            // Use CDATA helper for text elements
            self::addCdata($ad, 'title', $p['title']);
            self::addCdata($ad, 'url', BASE_URL . 'property/' . $p['slug']);
            self::addCdata($ad, 'content', $p['description'] ?: $p['title']);
            
            $ad->addChild('type', strtolower($p['listing_type']) === 'rent' ? 'rent' : 'for sale');
            
            $propTypeMap = [
                'apartment' => 'flat',
                'villa'     => 'house',
                'plot'      => 'land',
                'penthouse' => 'flat'
            ];
            $pType = strtolower($p['property_type']);
            $ad->addChild('property_type', $propTypeMap[$pType] ?? 'flat');
            
            $ad->addChild('price', (int)$p['price']);
            
            if ($p['bedrooms']) {
                $ad->addChild('rooms', $p['bedrooms']);
            }
            if ($p['bathrooms']) {
                $ad->addChild('bathrooms', $p['bathrooms']);
            }
            
            self::addCdata($ad, 'address', $p['address'] ?: ($p['city_name'] ?? ''));
            self::addCdata($ad, 'city', $p['city_name'] ?? '');
            self::addCdata($ad, 'region', $p['state_name'] ?? '');
            
            if ($p['pincode']) {
                $ad->addChild('postcode', $p['pincode']);
            }
            
            self::addCdata($ad, 'agency', $p['builder_name'] ?: 'PropertyRubix');

            // Add images
            $gallery = [];
            try {
                $gallery = json_decode($p['gallery_images'] ?? '[]', true) ?: [];
            } catch (Throwable $e) {}

            if ($p['thumbnail_image'] || !empty($gallery)) {
                $pictures = $ad->addChild('pictures');
                if ($p['thumbnail_image']) {
                    $pic = $pictures->addChild('picture');
                    self::addCdata($pic, 'picture_url', BASE_URL . $p['thumbnail_image']);
                }
                foreach ($gallery as $imgUrl) {
                    $pic = $pictures->addChild('picture');
                    self::addCdata($pic, 'picture_url', BASE_URL . $imgUrl);
                }
            }
        }

        echo $xml->asXML();
        exit;
    }

    /**
     * GET /api/v1/feeds/propertyfinder
     * Render compliant PropertyFinder XML listing feed.
     */
    public function propertyfinderXml(array $params = []): void {
        $this->authenticate('listings:read');
        $pdo = db();

        // Fetch properties (active only)
        $stmt = $pdo->query("
            SELECT p.*,
                   pr.name AS project_name, 
                   b.name AS builder_name, 
                   b.logo AS builder_logo,
                   c.name AS city_name, 
                   loc.name AS locality_name
            FROM properties p
            LEFT JOIN projects pr ON pr.id = p.project_id
            LEFT JOIN builders b ON b.id = p.builder_id
            LEFT JOIN cities c ON c.id = p.city_id
            LEFT JOIN localities loc ON loc.id = p.locality_id
            WHERE p.status = 'Active'
            ORDER BY p.id DESC
        ");
        $properties = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/xml; charset=utf-8');

        $xml = new SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><list></list>');

        foreach ($properties as $p) {
            $property = $xml->addChild('property');
            
            $property->addChild('reference_number', 'PR-' . $p['id']);
            $property->addChild('offering_type', strtolower($p['listing_type']) === 'rent' ? 'RR' : 'RS');
            
            $typeMap = [
                'apartment' => 'AP',
                'villa'     => 'VI',
                'plot'      => 'LD',
                'penthouse' => 'PH',
                'townhouse' => 'TH'
            ];
            $property->addChild('property_type', $typeMap[strtolower($p['property_type'])] ?? 'AP');
            
            self::addCdata($property, 'title', $p['title']);
            self::addCdata($property, 'description', $p['description'] ?: $p['title']);
            
            $property->addChild('price', (int)$p['price']);
            
            if ($p['bedrooms']) {
                $property->addChild('bedrooms', $p['bedrooms']);
            }
            if ($p['bathrooms']) {
                $property->addChild('bathrooms', $p['bathrooms']);
            }
            
            self::addCdata($property, 'city', $p['city_name'] ?? '');
            self::addCdata($property, 'locality', $p['locality_name'] ?? '');
            
            // Agent / Publisher
            $agent = $property->addChild('agent');
            self::addCdata($agent, 'name', $p['builder_name'] ?: 'PropertyRubix Agent');
            self::addCdata($agent, 'email', 'info@propertyrubix.com');

            // Photos
            $gallery = [];
            try {
                $gallery = json_decode($p['gallery_images'] ?? '[]', true) ?: [];
            } catch (Throwable $e) {}

            if ($p['thumbnail_image'] || !empty($gallery)) {
                if ($p['thumbnail_image']) {
                    $photo = $property->addChild('photo');
                    self::addCdata($photo, 'url', BASE_URL . $p['thumbnail_image']);
                }
                foreach ($gallery as $imgUrl) {
                    $photo = $property->addChild('photo');
                    self::addCdata($photo, 'url', BASE_URL . $imgUrl);
                }
            }
        }

        echo $xml->asXML();
        exit;
    }

    /**
     * Helper to append CDATA to a SimpleXMLElement.
     */
    private static function addCdata(SimpleXMLElement $node, string $name, string $value): void {
        $child = $node->addChild($name);
        $dom = dom_import_simplexml($child);
        $owner = $dom->ownerDocument;
        $dom->appendChild($owner->createCDATASection($value));
    }
}
