<?php
require_once "class/Database.php";
require_once "class/LoginAPI.php";


$userM = new UserM($pdo); 
$loginAPI = new LoginAPI($pdo);  
$loginAPI->handleLogin();      
?>