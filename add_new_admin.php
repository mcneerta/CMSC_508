<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}
//if($_SERVER["REQUEST_METHOD"] != "POST") {
    if (!isset($_GET['user_id'])) {

        // Retrieve list of users
        $stmt = $conn->prepare("SELECT user_id, first_name, last_name FROM tbl_user ORDER BY first_name, last_name");
        $stmt->execute();

        echo "<form method='get'>";
        echo "<select name='user_id;'>";

        while ($row = $stmt->fetch()) {
            echo "<option value='$row[user_id]'>$row[first_name] $row[last_name]</option>";
        }

        echo "</select>";
        echo "</form>";
    }
//}
else if($_SERVER["REQUEST_METHOD"] == "POST"){
    if (isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
    }

    if (isset($_POST['confirm'])) {
        $user_id = $_GET["user_id"];

        $stmt = $conn->prepare("insert ignore into tbl_admin values($user_id)");
        $stmt->bindValue(':user_id', $user_id);

        if ($stmt->execute()) {
            header("location: admin_dashboard.php?success=1");
        } else {
            echo "User is already an admin";
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Admin</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Add Admin</h2>
    <p>Please select a user to promote to admin.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="confirm" value="Confirm">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="home" value="HOME">
        </div>
    </form>
</div>
</body>
</html>
