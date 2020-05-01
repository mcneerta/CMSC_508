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
                $username = $_SESSION['username'];

                $sql_admins = "SELECT a.user_id AS id, b.username AS user, CONCAT( a.first_name,' ', a.last_name) AS name FROM tbl_user a INNER JOIN tbl_login b INNER JOIN  tbl_admin c ON a.login_id = b.login_id AND a.user_id = c.admin_id";
                $sql_users = "SELECT a.user_id AS id, b.username AS user, CONCAT( a.first_name,' ', a.last_name) AS name FROM tbl_user a INNER JOIN tbl_login b INNER JOIN  tbl_player c ON a.login_id = b.login_id AND a.user_id = c.player_id";



            } else {
                // Redirect user to user dashboard page
                header("location: login_redirect.php");
            }
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['home'])) {
        header("location: login_redirect.php");
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
    <h2>View All Users</h2>

    <p> Your Username: <?php echo $username ?> </p>

    <h3>Administrative Users</h3>
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Full Name</th>
        </tr>
    </table>
    <div class="scroll">
        <table>
            <?php
            if ($stmt = $conn->prepare($sql_admins)){
                if ($stmt->execute()){
                    while ($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<th>".$row['id']."</th>";
                        echo "<th>".$row['user']."</th>";
                        echo "<th>".$row['name']."</th>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </table>
    </div>
    <h3>All Players</h3>
    <table border="1">
        <tr>
            <th>User ID</th>
            <th>Username</th>
            <th>Full Name</th>
        </tr>
    </table>
    <div class="scroll">
        <table>
            <?php
            if ($stmt = $conn->prepare($sql_users)){
                if ($stmt->execute()){
                    while ($row = $stmt->fetch()) {
                        echo "<tr>";
                        echo "<th>".$row['id']."</th>";
                        echo "<th>".$row['user']."</th>";
                        echo "<th>".$row['name']."</th>";
                        echo "</tr>";
                    }
                }
            }
            ?>
        </table>
    </div>
    
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="home" value="Home">
        </div>

    </form>




