<?php
session_start();
// echo dirname(__DIR__) . '/include/config.php';

include dirname(__DIR__) . '/include/config.php';

$database = new Database(DB_HOST, DB_NAME, DB_USER, DB_PASS);
$pdo = $database->getPDOConnect();
