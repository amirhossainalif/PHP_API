<?php
class DashboardAPI {
    private $pdo;
    private $userM;

    public function __construct($pdo) {
        $this->pdo = $pdo;
        $this->userM = new UserM($this->pdo);
    }

    public function authorize() {
        session_start();
        header('Content-Type: application/json');

        if (!isset($_SESSION['Email']) || !isset($_SESSION['user'])) {
            echo json_encode(["error" => "Unauthorized access. Please log in first."]);
            exit();
        }
    }

    public function handleRequest() {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                $this->handleGet();
                break;

            case 'POST':
                $this->handlePost();
                break;

            case 'PUT':
                $this->handlePut();
                break;

            case 'DELETE':
                $this->handleDelete();
                break;

            default:
                echo json_encode(["error" => "Invalid request method"]);
                break;
        }
    }

    private function handleGet() {
        if (isset($_GET['id'])) {
            $user = $this->userM->getUserById($_GET['id']);
            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } else {
            $users = $this->userM->getAllUser();
            echo json_encode($users);
        }
    }

    private function handlePost() {
        if (isset($_POST['Name'], $_POST['Email'], $_POST['Password'])) {
            $Name = trim($_POST['Name']);
            $Email = trim($_POST['Email']);
            $Password = trim($_POST['Password']);

            if (empty($Name) || empty($Email) || empty($Password)) {
                echo json_encode(["error" => "All fields are required"]);
                exit();
            }

            if ($this->userM->create($Name, $Email, $Password)) {
                echo json_encode(["success" => "User created successfully"]);
            } else {
                echo json_encode(["error" => "Failed to create user"]);
            }
        } else {
            echo json_encode(["error" => "Missing parameters"]);
        }
    }

    private function handlePut() {
        $putData = json_decode(file_get_contents("php://input"), true);
        
        if (isset($putData['id'], $putData['name'], $putData['email'], $putData['password'])) {
            $id = $putData['id'];
            $Name = trim($putData['name']);
            $Email = trim($putData['email']);
            $Password = trim($putData['password']);
        
            if (empty($Name) || empty($Email) || empty($Password)) {
                echo json_encode(["error" => "All fields are required"]);
                exit();
            }
        
            if ($this->userM->updateUser($id, $Name, $Email, $Password)) {
                echo json_encode(["success" => "User updated successfully"]);
            } else {
                echo json_encode(["error" => "Failed to update user"]);
            }
        } else {
            echo json_encode(["error" => "Missing parameters"]);
        }
    }

    private function handleDelete() {
        if (isset($_GET['Email'])) {
            $Email = trim($_GET['Email']);
            if ($this->userM->DeleteUser($Email)) {
                echo json_encode(["success" => "User deleted successfully"]);
            } else {
                echo json_encode(["error" => "Failed to delete user"]);
            }
        } else {
            echo json_encode(["error" => "Email parameter is required"]);
        }
    }
}
?>
