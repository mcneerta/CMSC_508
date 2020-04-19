<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "project_16";
$password = "V00880399";
$database = "project_16";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$database", $username, $password)
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e){
    echo "Connection Failed: " . $e->getMessage();
}

?>