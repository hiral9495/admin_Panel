<?php
require_once "../config/database.php";
require_once "../models/User.php";
require '../vendor/autoload.php'; // Include PHPMailer via Composer
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UserController
{
    private $db;
    private $requestMethod;
    private $action;

    public function __construct($db, $requestMethod, $action)
    {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->action = $action;
    }

    public function processRequest()
    {
        header("Content-Type: application/json");

        switch ($this->action) {
            case "register":
                if ($this->requestMethod === "POST") {
                    $this->register();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for register action",
                    ]);
                }
                break;
            case "login":
                if ($this->requestMethod === "POST") {
                    $this->login();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for login action",
                    ]);
                }
                break;
            case "loginAs":
                if ($this->requestMethod === "POST") {
                    $this->loginAs();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for login action",
                    ]);
                }
                break;
            case "roles":
                if ($this->requestMethod === "GET") {
                    $this->getRoles();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for roles action",
                    ]);
                }
                break;
            case "userList":
                if ($this->requestMethod === "GET") {
                    $this->userList();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for userList action",
                    ]);
                }
                break;
            case "profileUpdate":
                if ($this->requestMethod === "POST") {
                    $this->profileUpdate();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for userList action",
                    ]);
                }
                break;
            case "passwordChange":
                if ($this->requestMethod === "POST") {
                    $this->passwordChange();
                } else {
                    echo json_encode([
                        "error" => "Invalid request method for userList action",
                    ]);
                }
                break;
            default:
                $this->notFoundResponse();
                break;
        }
    }

    private function register()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $response = $this->validateRegister($input);

        if (!$response["success"]) {
            http_response_code(400); // Set the HTTP response code to 400 for bad request
            echo json_encode(["errors" => $response["errors"]]);
            return;
        }

        $user = new User($this->db);
        $user->name = $input["name"];
        $user->email = $input["email"];
        $user->password = $input["password"];
        $user->userRole = $input["userRole"];

        if ($user->emailExists($user->email)) {
            echo json_encode(["errors" => ["email" => "Email already exists"]]);
            return;
        }

        if ($user->create()) {
            echo json_encode(["message" => "Registration successful"]);
        } else {
            echo json_encode(["errors" => ["Unable to create account"]]);
        }
    }

    private function validateRegister($input)
    {
        $errors = [];

        if (!isset($input["name"]) || empty($input["name"])) {
            $errors["name"] = "Username is required";
        }
        if (
            !isset($input["email"]) ||
            !filter_var($input["email"], FILTER_VALIDATE_EMAIL)
        ) {
            $errors["email"] = "Valid email is required";
        }
        if (!isset($input["password"]) || empty($input["password"])) {
            $errors["password"] = "Password is required";
        }elseif (strlen($input["password"]) < 6) {
            $errors["password"] = "Password must be at least 6 characters long";
        }
        if (
            !isset($input["confirmPassword"]) ||
            empty($input["confirmPassword"])
        ) {
            $errors["confirmPassword"] = "Confirm password is required";
        } elseif ($input["password"] !== $input["confirmPassword"]) {
            $errors["confirmPassword"] = "Passwords do not match";
        }

        return [
            "success" => empty($errors),
            "errors" => $errors,
        ];
    }

    private function notFoundResponse()
    {
        header("HTTP/1.1 404 Not Found");
        echo json_encode(["message" => "Page not found"]);
    }

    private function login()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $response = $this->validateLogin($input);

        if (!$response["success"]) {
            http_response_code(400); // Set the HTTP response code to 400 for bad request
            echo json_encode(["errors" => $response["errors"]]);
            return;
        }

        $user = new User($this->db);
        $user->email = $input["username"];
        $user->password = $input["password"];

        if ($user->login()) {
            $_SESSION["user_id"] = $user->id;
            $_SESSION["user_email"] = $user->email;
            $_SESSION["username"] = $user->name;
            $_SESSION["userrole"] = $user->userRole;
            $_SESSION["is_authenticated"] = true;

            echo json_encode(["message" => "Login successful"]);
        } else {
            echo json_encode([
                "errors" => ["genral" => "Invalid email or password"],
            ]);
        }
    }

    private function validateLogin($input)
    {
        $errors = [];

        if (!isset($input["username"]) || empty($input["username"])) {
            $errors["email"] = "Email is required";
        }
        if (!isset($input["password"]) || empty($input["password"])) {
            $errors["password"] = "Password is required";
        }

        return [
            "success" => empty($errors),
            "errors" => $errors,
        ];
    }

    private function getRoles()
    {
        $user = new User($this->db);
        $roles = $user->getUserRoles();
        echo json_encode($roles);
    }

    public function userList()
    {
        $user = new User($this->db);
        $users = $user->getUsersWithRoles();
        return $users;
    }

    public function loginAs()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $response = $this->validateLogin($input);
        $userId = $input["id"];
        // Get the Super Admin's session data
        if ($_SESSION["userrole"] === 1) {
            $_SESSION["super_admin_backup"] = [
                "user_id" => $_SESSION["user_id"],
                "user_email" => $_SESSION["user_email"],
                "username" => $_SESSION["username"],
                "userrole" => $_SESSION["userrole"],
                "is_authenticated" => $_SESSION["is_authenticated"],
            ];
        }
        $currentRole = $_SESSION["userrole"];

        // Get the user to log in as
        $query = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $query->bind_param("i", $userId);
        $query->execute();
        $result = $query->get_result();
        $user = $result->fetch_assoc();

        if (!$user) {
            return ["error" => "User not found"];
        }

        // Log in as the new user
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["user_email"] = $user["email"];
        $_SESSION["username"] = $user["name"];
        $_SESSION["userrole"] = $user["user_role"];
        $_SESSION["is_authenticated"] = true;

        // Regenerate session ID to prevent session fixation attacks
        session_regenerate_id(true);
        echo json_encode(["message" => "Login successful"]);
    }

    private function profileUpdate()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $response = $this->validateProfileDetail($input);

        if (!$response["success"]) {
            http_response_code(400); // Set the HTTP response code to 400 for bad request
            echo json_encode(["errors" => $response["errors"]]);
            return;
        }

        $user = new User($this->db);
        $user->name = $input["name"];
        $user->email = $input["email"];
        $user->id = $_SESSION["user_id"];

        if ($user->updateUser()) {
            $_SESSION["user_email"] = $input["email"];
            $_SESSION["username"] = $input["name"];
            echo json_encode(["message" => "Profile update successfully"]);
        } else {
            echo json_encode(["errors" => ["Unable to Profile update"]]);
        }
    }

    private function validateProfileDetail($input)
    {
        $errors = [];

        if (!isset($input["name"]) || empty($input["name"])) {
            $errors["name"] = "name is required";
        }
        if (
            !isset($input["email"]) ||
            !filter_var($input["email"], FILTER_VALIDATE_EMAIL)
        ) {
            $errors["email"] = "Password is required";
        }

        return [
            "success" => empty($errors),
            "errors" => $errors,
        ];
    }

    private function passwordChange()
    {
        $input = json_decode(file_get_contents("php://input"), true);
        $response = $this->validatePassword($input);

        if (!$response["success"]) {
            http_response_code(400); // Set the HTTP response code to 400 for bad request
            echo json_encode(["errors" => $response["errors"]]);
            return;
        }

        $user = new User($this->db);
        $user->password = $input["password"];
        $user->id = $_SESSION["user_id"];

        if ($user->updateUserPassword()) {
            echo json_encode(["message" => "Password change successfully"]);
        } else {
            echo json_encode(["errors" => ["Unable to Password change"]]);
        }
    }

    private function validatePassword($input)
    {
        $errors = [];

        if (!isset($input["password"]) || empty($input["password"])) {
            $errors["password"] = "Password is required";
        }elseif (strlen($input["password"]) < 6) {
            $errors["password"] = "Password must be at least 6 characters long";
        }
        if (
            !isset($input["confirmPassword"]) ||
            empty($input["confirmPassword"])
        ) {
            $errors["confirmPassword"] = "Confirm password is required";
        } elseif ($input["password"] !== $input["confirmPassword"]) {
            $errors["confirmPassword"] = "Passwords do not match";
        }

        return [
            "success" => empty($errors),
            "errors" => $errors,
        ];
    }
}
?>
