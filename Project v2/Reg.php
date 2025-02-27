<?php
require_once "class/Database.php"; 
require_once "class/RegistrationAPI.php"; 

$registrationAPI = new RegistrationAPI($pdo); 
$registrationAPI->handleRegistration(); 
?>

