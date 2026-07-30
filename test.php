<?php
require "config/db.php";
$pdo = db();
$stmt = $pdo->query("
    SELECT p.id, p.name, p.city_id, p.locality_id, p.location_area, c.name as city_name 
    FROM projects p 
    JOIN cities c ON c.id = p.city_id 
    WHERE c.name = 'Mumbai'
");
echo "<pre>Projects in Mumbai:\n";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
