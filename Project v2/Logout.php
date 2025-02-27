<?php
    session_start();

    header('Content-Type: application/json');

    session_destroy();
    $response = ["success" => true, "message" => "Logged out successfully"];
    echo json_encode($response);
?>