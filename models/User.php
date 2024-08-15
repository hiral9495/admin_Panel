<?php
require_once '../config/database.php';
ini_set('error_log', 'custom-error.log');
class User {
   
    private $conn;
    private $table_name = "users";
    public $id;
    public $name;
    public $email;
    public $password;
    public $userRole;

    public function __construct($db) {
        $this->conn = $db;
    }
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " (name, email, password, user_role) VALUES (?, ?, ?, ?)";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        $this->name = htmlspecialchars(strip_tags($this->name));
        $this->email = htmlspecialchars(strip_tags($this->email));
        $this->password = htmlspecialchars(strip_tags($this->password));
        $this->userRole = htmlspecialchars(strip_tags($this->userRole));

        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
    
        $stmt->bind_param('sssi', $this->name, $this->email, $hashed_password, $this->userRole);

        if ($stmt->execute()) {
            return true;
        } else {
            error_log("Error executing statement: " . $stmt->error);
            return false;
        }
    }

    public function readOne() {
        $query = "SELECT id, name, email, password FROM " . $this->table_name . " WHERE email = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }
        
        $this->username = htmlspecialchars(strip_tags($this->name));

        $stmt->bind_param('s', $this->name);

        if ($stmt->execute()) {

            $result = $stmt->get_result();

            if ($row = $result->fetch_assoc()) {
                $this->id = $row['id'];
                $this->username = $row['name'];
                $this->email = $row['email'];
                $this->password = $row['password'];
                return true;
            }
        } else {
            error_log("Error executing statement: " . $stmt->error);
        }
        
        return false;
    }
    
    public function login() {
        $query = "SELECT id, name, user_role, password FROM users WHERE email = ? LIMIT 1";
        if ($stmt = $this->conn->prepare($query)) {
            $stmt->bind_param("s", $this->email);

            if ($stmt->execute()) {
                $stmt->bind_result($id, $name, $userRole, $hashedPassword);

                $fetch_result = $stmt->fetch();
    
                if ($fetch_result) {
                    $this->password = htmlspecialchars(strip_tags($this->password));
                   if (password_verify($this->password, $hashedPassword)) {
                    $this->id = $id;
                    $this->name = $name;
                    $this->userRole = $userRole;
                        $stmt->close();
                        return true;
                    } else {
                        return false;
                    }
                } 
    
                $stmt->close();
            } else {
                echo "Statement execution failed: " . $stmt->error . "<br>";
            }
        } else {
            echo "Statement preparation failed: " . $this->conn->error . "<br>";
        }
    
        return false;
    }
    
    public function emailExists($email) {
        $query = "SELECT id FROM " . $this->table_name . " WHERE email = ? LIMIT 1";
    
        $stmt = $this->conn->prepare($query);

        if ($stmt === false) {
            error_log("Error preparing statement: " . $this->conn->error);
            return false;
        }

        $stmt->bind_param('s', $email);

        $stmt->execute();

        $stmt->store_result();
    
        $exists = $stmt->num_rows > 0;
    
        $stmt->close();
    
        return $exists;
    }

    public function getUserRoles() {
        $query = "SELECT id, user_type FROM user_roles";
        $result = $this->conn->query($query);

        if ($result === false) {
            die("Error executing query: " . $this->conn->error);
        }

        $roles = [];
        while ($row = $result->fetch_assoc()) {
            $roles[] = $row;
        }

        return $roles;
    }

    public function getUsersWithRoles() {
        $query = "SELECT u.*, ur.user_type 
                  FROM users u
                  JOIN user_roles ur ON u.user_role = ur.id
                  ORDER BY u.id ASC";
        
        $stmt = $this->conn->query($query);

        $users = [];
        if ($stmt) {
            while ($row = $stmt->fetch_assoc()) {
                $users[] = $row;
            }
        } else {
            echo "Error: " . $this->conn->error;
        }

        return $users;
    }

    public function updateUser() {
        $query = "UPDATE " . $this->table_name . " SET name =?, email =? where id =?";

        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("ssi", $this->name, $this->email, $this->id);
    
        return $stmt->execute();
    }

    public function updateUserPassword() {
        $query = "UPDATE " . $this->table_name . " SET password =? where id =?";

        $stmt = $this->conn->prepare($query);
        $this->password = htmlspecialchars(strip_tags($this->password));

        $hashed_password = password_hash($this->password, PASSWORD_DEFAULT);
    
        $stmt->bind_param("si", $hashed_password, $this->id);
    
        return $stmt->execute();
    }
}
