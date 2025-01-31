<?php
session_start();
include 'config.php'; 


if (isset($_COOKIE['remember_token'])) {
    $token = $_COOKIE['remember_token'];
    $sql = "UPDATE users SET token = NULL WHERE token = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $token);
    $stmt->execute();
    $stmt->close();
    
 
    setcookie('remember_token', '', time() - 3600, '/');
}


session_unset();
session_destroy();


header("Location: index.php");
exit();
