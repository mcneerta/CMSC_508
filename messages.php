<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$sql = "SELECT a.message, a.time_stamp, c.username 
FROM tbl_messages a 
    INNER JOIN tbl_user b 
    INNER JOIN tbl_login c 
        ON a.contributor_id = b.user_id 
               AND b.login_id = c.login_id 
WHERE a.chatroom_id = :chosen_chatroom 
  AND a.visible > 0
ORDER BY a.time_stamp DESC;";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['goback'])) {
        // Redirect user to welcome page
        header("location: chatroom.php");
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
    <h2>Chatrooms</h2>

    <p>Currently Unavailable</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="goback" value="GO BACK">
        </div>
    </form>


</div>
</body>
</html>