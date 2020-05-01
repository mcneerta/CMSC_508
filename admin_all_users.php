<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

// if not post, first run query to verify admin user is logged in
if($_SERVER["REQUEST_METHOD"] != "POST") {
    $sql = "SELECT a.login_id, b.username FROM tbl_user a INNER JOIN tbl_login b INNER JOIN  tbl_admin c ON a.login_id = b.login_id AND a.user_id = c.admin_id WHERE a.login_id = :login_id";
    if ($stmt = $conn->prepare($sql)){
        $stmt->bindValue(':login_id', $_SESSION["id"]);
        if ($stmt->execute()){

            // if query proves user is admin/ continue to load page else go back to login redirect
            if($stmt->rowCount() == 1){
//                $username = $_SESSION['username'];



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

    if(isset($_POST['logout'])) {
        echo "This will be a Logout";
        header("location: logout.php");
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
    <h2>All Users</h2>

    <p> Username: <?php echo $username ?> </p>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="userprofile" value="Go To My Profile">
        </div>

    </form>




