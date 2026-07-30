<?php
require "config/db.php";
$pdo = db();
$stmt = $pdo->query("SELECT id, name, city_id, locality_id FROM projects WHERE city_id=2");
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
