<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
//echo $_SESSION['userrole'];
//include '../views/dashboard.php';
require_once '../config/database.php';
require_once '../controllers/UserController.php';


if (isset($_SESSION['user_id']) && isset($_SESSION['userrole'])) {
switch ($_SESSION['userrole']) {
    case 1:
        include '../views/superAdmin.php';
        break;
       // exit();
    case 2:
        include '../views/admin.php';
        break;
        //exit();y
    case 3:
         include '../views/editor.php';
         break;
        //exit();
    case 4:
         include '../views/member.php';
         break;
        //exit();
    default:
    header("Location: login.php");
    exit();
}
}
?>


