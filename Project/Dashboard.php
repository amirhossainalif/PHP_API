<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['Email']) || !isset($_SESSION['user'])) {
    echo json_encode(["error" => "Unauthorized access. Please log in first."]);
    exit();
}

require_once "class/Database.php";
$userM = new UserM($pdo);

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'GET':
        if (isset($_GET['id'])) {
            $user = $userM->getUserById($_GET['id']);
            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } else {
            $users = $userM->getAllUser();
            echo json_encode($users);
        }
        break;

    case 'POST':
        if (isset($_POST['Name'], $_POST['Email'], $_POST['Password'])) {
            $Name = trim($_POST['Name']);
            $Email = trim($_POST['Email']);
            $Password = trim($_POST['Password']);

            if (empty($Name) || empty($Email) || empty($Password)) {
                echo json_encode(["error" => "All fields are required"]);
                exit();
            }

            if ($userM->create($Name, $Email, $Password)) {
                echo json_encode(["success" => "User created successfully"]);
            } else {
                echo json_encode(["error" => "Failed to create user"]);
            }
        } else {
            echo json_encode(["error" => "Missing parameters"]);
        }
        break;

    case 'PUT':
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
        
        if ($userM->updateUser($id, $Name, $Email, $Password)) {
            echo json_encode(["success" => "User updated successfully"]);
        } else {
            echo json_encode(["error" => "Failed to update user"]);
        }
        } else {
            echo json_encode(["error" => "Missing parameters"]);
        }
        break;
        

    case 'DELETE':
        if (isset($_GET['Email'])) {
            $Email = trim($_GET['Email']);
            if ($userM->DeleteUser($Email)) {
                echo json_encode(["success" => "User deleted successfully"]);
            } else {
                echo json_encode(["error" => "Failed to delete user"]);
            }
        } else {
            echo json_encode(["error" => "Email parameter is required"]);
        }
        break;

    default:
        echo json_encode(["error" => "Invalid request method"]);
        break;
}
?>