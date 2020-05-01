<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$sql = "SELECT chatroom_id, title FROM tbl_chatroom WHERE status = 1; ";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
    }

    if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                if(isset($_POST['chat_'.$row['chatroom_id']])) {
                    $_SESSION['chosen_chatroom'] = $row['chatroom_id'];
//                    echo "<p>Sending you to the chosen chatroom in the future</p>";
                    header("location: messages.php");
                }
            }
        }
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
        .btn-primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            height: 50px;
            width: 200px;
            margin: 2px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Chatrooms</h2>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


        <?php
        if ($stmt = $conn->prepare($sql)){
            if ($stmt->execute()){
                while ($row = $stmt->fetch()) {

                    echo "<div class='form-group'>";
                    $str = '<input type="submit" class="btn btn-primary" name="chat_'.$row['chatroom_id'].'" value="'.$row['title'].'"';
                    echo $str;
                    echo "</div>";
                }
            }
        }
        ?>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="home" value="HOME">
        </div>
    </form>
</div>
</body>
</html>



