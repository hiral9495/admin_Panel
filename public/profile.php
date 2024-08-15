
<?php
session_start();
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
$controller = new UserController($db, $_SERVER["REQUEST_METHOD"], $action);
$controller->processRequest();
exit;
}

include '../views/profile.php';
?>
