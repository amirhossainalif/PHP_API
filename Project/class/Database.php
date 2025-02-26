<?php
$host = 'localhost';
$dbname = 'CRUD';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected successfully!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}


class UserM {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function create($Name, $Email, $Password) {
        $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("INSERT INTO user (Name, Email, Password) VALUES (:name, :email, :password)");
        $stmt->bindParam(':name', $Name);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':password', $hashed_password);
        return $stmt->execute();
    }

    public function getUser($Email) {
        $stmt = $this->pdo->prepare("SELECT Name, Email, Password FROM user WHERE Email = :email");
        $stmt->bindParam(':email', $Email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // public function getAllUser() {
    //     $stmt = $this->pdo->prepare("SELECT * FROM user");
    //     $stmt->execute();
    //     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    // }

    public function getAllUser() {
        $stmt = $this->pdo->prepare("SELECT id, Name, Email FROM user");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function DeleteUser($Email) {
        $stmt = $this->pdo->prepare("DELETE FROM user WHERE Email = :email");
        $stmt->bindParam(':email', $Email);
        
        return $stmt->execute();
    }

    public function updateUser($id, $Name, $Email, $Password) {
        $hashed_password = password_hash($Password, PASSWORD_DEFAULT);
        $stmt = $this->pdo->prepare("UPDATE user SET Name = :name, Email = :email, Password = :password WHERE id = :id");
        $stmt->bindParam(':name', $Name);
        $stmt->bindParam(':email', $Email);
        $stmt->bindParam(':password', $hashed_password);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
}

?>
