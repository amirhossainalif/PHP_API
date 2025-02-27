<?php
session_start();
header('Content-Type: application/json');

class LoginAPI {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function handleLogin() {
        $data = json_decode(file_get_contents("php://input"), true);

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $isValid = true;

            $Email = isset($data["email"]) ? $data["email"] : '';
            $Password = isset($data["password"]) ? $data["password"] : '';

            if (empty($Email)) {
                $isValid = false;
                $response = ["error" => "Email is required"];
            }

            if (empty($Password)) {
                $isValid = false;
                $response = ["error" => "Password is required"];
            }

            if ($isValid === true) {
                $userM = new UserM($this->pdo);

                $user = $userM->getUser($Email);

                if ($user && password_verify($Password, $user['Password'])) {
                    $_SESSION['Email'] = $Email;
                    $_SESSION['user'] = $user['Name'];

                    $response = ["success" => true, "message" => "Login successful"];
                } else {
                    $response = ["error" => "Invalid credentials"];
                }
            }
        } else {
            $response = ["error" => "Invalid request method"];
        }

        echo json_encode($response);
    }
}
?>
