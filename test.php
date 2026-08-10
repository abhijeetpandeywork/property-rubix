<?php
require "config/db.php";
$pdo = db();
$stmt = $pdo->query("SHOW COLUMNS FROM projects");
echo "<pre>";
print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
echo "</pre>";
