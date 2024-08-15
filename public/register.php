<?php
require_once '../config/database.php';
require_once '../config/config.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();
if ($_SERVER["REQUEST_METHOD"] === 'POST') {
$controller = new UserController($db, $_SERVER["REQUEST_METHOD"], 'register');
$controller->processRequest();
exit;
}
$action = isset($_GET['action']) ? $_GET['action'] : '';
if ($_SERVER["REQUEST_METHOD"] === 'GET' && $action === 'roles') {
    $controller = new UserController($db, $_SERVER["REQUEST_METHOD"], 'roles');
    $controller->processRequest();
    exit;
}
?>

<?php include '../views/register.php'; ?>
