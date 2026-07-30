<?php
require "config/db.php";
$pdo = db();
$stmt = $pdo->query("SELECT id, name, slug FROM localities WHERE city_id=5");
echo "<pre>Localities for Mumbai (id=5):\n";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
