<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if($_SERVER["REQUEST_METHOD"] != "POST") {
    $sql = "SELECT a.login_id, b.username FROM tbl_user a INNER JOIN tbl_login b INNER JOIN  tbl_admin c ON a.login_id = b.login_id AND a.user_id = c.admin_id WHERE a.login_id = :login_id";

    if ($stmt = $conn->prepare($sql)){
        $stmt->bindValue(':login_id', $_SESSION["id"]);
        if ($stmt->execute()){
            if($stmt->rowCount() == 1){
                $username = $_SESSION['username'];
            } else {
                // Redirect user to user dashboard page
                header("location: login_redirect.php");
            }
        }
    }
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['userprofile'])) {
        echo "Go To User Profile";
        header("location: editAccount.php");
    }
    if(isset($_POST['leaderBoard'])) {
        echo "Go To Leader Board";
        // Redirect user to welcome page
        header("location: user_leaderboard.php");
    }
    if(isset($_POST['all_quests'])) {
        echo "Go To All Quests";
        
    }
    if(isset($_POST['all_users'])) {
        header("location: admin_all_users.php");
    }
    if(isset($_POST['chatrooms'])) {
        header("location: chatrooms.php");
    }
    if(isset($_POST['logout'])) {
        echo "This will be a Logout";
        header("location: logout.php");
    }
    if(isset($_POST['delete_acc'])) {
        header("location: deleteAccount.php");
    }
}

    ?>



<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Administrative Dashboard</h2>

    <p> Username: <?php echo $username ?> </p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="leaderBoard" value="Go To Leader Board">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="all_quests" value="See All Quests">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="all_users" value="View Users">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="chatrooms" value="Go Chat Rooms">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="userprofile" value="Update User Info">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="logout" value="Log out">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="delete_acc" value="Delete Account">
        </div>
    </form>




