<?php
session_start();
header('Content-Type: application/json');

class RegistrationAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleRegistration() {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $isValid = true;

            $Name = isset($data["name"]) ? $data["name"] : '';
            $Email = isset($data["email"]) ? $data["email"] : '';
            $Password = isset($data["password"]) ? $data["password"] : '';

            if (empty($Name)) {
                $isValid = false;
                $response = ["error" => "Name is required"];
            }

            if (empty($Email)) {
                $isValid = false;
                $response = ["error" => "Email is required"];
            }

            if (empty($Password)) {
                $isValid = false;
                $response = ["error" => "Password is required"];
            }

            if ($isValid === true) {
                require_once "class/Database.php"; 

                $userM = new UserM($this->pdo); 

                $existingUser = $userM->getUser($Email);

                if (!$existingUser) {
                    if ($userM->create($Name, $Email, $Password)) {
                        $response = ["success" => true, "message" => "Registration successful"];
                    } else {
                        $response = ["error" => "Registration failed"];
                    }
                } else {
                    $response = ["error" => "User already registered"];
                }
            }
        } else {
            $response = ["error" => "Invalid request method"];
        }

        echo json_encode($response);
    }
}
?>
