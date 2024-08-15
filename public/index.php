<?php
session_start();
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Define your routes
$routes = [
    '/login' => 'public/login.php',
    '/dashboard' => 'dashboard.php',
    '/register' => 'register.php',
    '/userList' => 'userList.php',
    '/logout' => 'logout.php',
    '/profile' => 'profile.php',
];

// Check if the requested path exists in your routes
if (array_key_exists($path, $routes)) {
    // Include the corresponding file
    include $routes[$path];
} else {
    // Handle 404 error if route is not found
    http_response_code(404);
    include '404.php';
}

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: /login");
    exit();
} else {

// If user is logged in, redirect to the dashboard
header("Location: /dashboard");
exit();
}
// Get the requested URL path

?>