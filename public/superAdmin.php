<?php
session_start();
if ($_SERVER["REQUEST_METHOD"] == "POST") {
if (isset($_SESSION['super_admin_backup'])) {
    // Restore Super Admin session
    $_SESSION['user_id'] = $_SESSION['super_admin_backup']['user_id'];
    $_SESSION['user_email'] = $_SESSION['super_admin_backup']['user_email'];
    $_SESSION['username'] = $_SESSION['super_admin_backup']['username'];
    $_SESSION['userrole'] = $_SESSION['super_admin_backup']['userrole'];
    $_SESSION['is_authenticated'] = $_SESSION['super_admin_backup']['is_authenticated'];

    // Remove the backup session data
    unset($_SESSION['super_admin_backup']);
    echo 'ok';

    // Redirect to the Super Admin dashboard
    header("Location: dashboard");
    exit();
}
// } else {
//     // If no backup exists, redirect to login
//     header("Location: login.php");
//     exit();
// }
}
?>