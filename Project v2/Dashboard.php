<?php
require_once "class/Database.php"; 
require_once "class/DashboardAPI.php";  

$userM = new UserM($pdo); 

$dashboardAPI = new DashboardAPI($pdo);

$dashboardAPI->authorize();

$dashboardAPI->handleRequest();
?>
