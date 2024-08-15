
<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || (isset($_SESSION['userrole']) && $_SESSION['userrole'] !== 1)) {
    unset($_SESSION['user_id']);
    header("Location: login");
    exit();
}
require_once '../config/database.php';
require_once '../controllers/UserController.php';

$database = new Database();
$db = $database->getConnection();

if ($_SERVER["REQUEST_METHOD"] === 'GET') {
  $controller = new UserController($db, $_SERVER["REQUEST_METHOD"], 'userList');
  $users = $controller->userList();
   include  '../views/userList.php';
}

if ($_SERVER["REQUEST_METHOD"] === 'POST') {
    $controller = new UserController($db, $_SERVER["REQUEST_METHOD"], 'loginAs');
    $controller->loginAs();
    exit;
}
?>