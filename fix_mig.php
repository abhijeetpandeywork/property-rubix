<?php
require 'config/db.php';
db()->query("INSERT IGNORE INTO migrations (migration) VALUES ('011_add_project_details_fields.sql')");
echo "Fixed 011";
