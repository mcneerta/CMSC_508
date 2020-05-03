<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if (!isset($_GET['user_id'])) {

    // Retrieve list of users
    $stmt = $conn->prepare("SELECT user_id, first_name, last_name FROM tbl_user ORDER BY first_name, last_name");
    $stmt->execute();

    echo "<form method='get'>";
    echo "<select name='user_id' onchange='this.form.submit();'>";

    while ($row = $stmt->fetch()) {
        echo "<option value='$row[user_id]'>$row[first_name] $row[last_name]</option>";
    }

    echo "</select>";
    echo "</form>";
}

else {

    $user_id = $_GET["user_id"];

    $stmt = $conn->prepare("insert ignore into tbl_admin values($user_id)");
    $stmt->bindValue(':user_id', $user_id);

    if ($stmt->execute()) {
        header("location: admin_dashboard.php?success=1");
    }

    else{
        echo "User is already an admin";
    }
}

?>