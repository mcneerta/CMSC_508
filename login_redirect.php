<?php

// Include config file
require_once "connection.php";
session_start();



$stmt = $conn->prepare("
                    SELECT login_id FROM tbl_user WHERE login_id = :login_id");
$stmt->bindValue(':login_id', $_SESSION['id']);

if ($stmt->execute()){
    if($stmt->rowCount() == 0){
        // Redirect user to new profile page
        header("location: user_newprof.php");
    } else {
        $stmt1 = $conn->prepare("
                    SELECT admin_id FROM tbl_admin
                     WHERE admin_id = (
                                    SELECT user_id FROM tbl_user WHERE login_id = :login_id)");
        $stmt1->bindValue(':login_id', $_SESSION['id']);

        if ($stmt1->execute()){
            if($stmt1->rowCount() == 1){
                echo "Should be redirected to admin dashboard";
            } else {
                // Redirect user to user dashboard page
                header("location: user_dashboard.php");
            }
        }
    }
}


// Close statement
unset($stmt);


echo "Something went wrong";

// Close statement
unset($stmt1);

// Close Connection
unset($conn);

?>