<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
$controller = new UserController($db, $_SERVER["REQUEST_METHOD"], 'login');
$controller->processRequest();
exit;
}

include '../views/login.php';
?>
