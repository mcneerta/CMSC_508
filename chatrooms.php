<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

if ($stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE login_id = :login_id ;")) {
    $stmt->bindValue(':login_id', $_SESSION['id']);
    if ($stmt->execute()) {
        if ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
    }
}

$isAdmin = false;
$addingRoom = false;

if($_SERVER["REQUEST_METHOD"] != "POST") {
    $sql = "SELECT a.login_id, b.username 
            FROM tbl_user a INNER JOIN tbl_login b INNER JOIN  tbl_admin c 
                ON a.login_id = b.login_id AND a.user_id = c.admin_id 
            WHERE a.login_id = :login_id";
    if ($stmt = $conn->prepare($sql)){
        $stmt->bindValue(':login_id', $_SESSION["id"]);
        if ($stmt->execute()){
            if($stmt->rowCount() == 1){
                $isAdmin = true;
            }
        }
    }
}

$sql = "SELECT chatroom_id, title FROM tbl_chatroom WHERE status = 1; ";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
    }

    if(isset($_POST['addroom'])) {
        $addingRoom = true;
    }

    if(isset($_POST['submitNewRoom']) && isset($_POST['newChatRoom'])) {
        $addingRoom = false;
        $sql_add = "INSERT INTO tbl_chatroom(title, status, created_by_id) VALUES(:title, :status, :creator_id);";

        $title = trim($_POST["newChatRoom"]);
        if ($stmt = $conn->prepare($sql_add)){
            $stmt->bindValue(':title', $title, PDO::PARAM_STR);
            $stmt->bindValue(':status', 1);
            $stmt->bindValue(':creator_id', $user_id);
            if ($stmt->execute()){
                if($stmt->rowCount() == 1){
                    $isAdmin = true;
                }
            }
        }
    }
    if ($stmt = $conn->prepare($sql)) {
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                if(isset($_POST['chat_'.$row['chatroom_id']])) {
                    $_SESSION['chosen_chatroom'] = $row['chatroom_id'];
//                    echo "<p>Sending you to the chosen chatroom in the future</p>";
                    if(!$isAdmin) {
                        header("location: messages.php");
                    }
                    else{
                        header("location: admin_messages.php");
                    }
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
    <title>Chatrooms</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
        .btn_chatroom {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            height: 50px;
            width: 200px;
            margin: 2px;
        }
        .btn_primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            height: 50px;
            width: 200px;
            margin: 2px auto;

        }
        .background_container {
            border: 2px solid #dedede;
            background-color: #f1f1f1;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            width: 230px;
        }
        .w{
            width: 230px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Chatrooms</h2>


    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

        <div class="background_container">
        <?php

        if ($stmt = $conn->prepare($sql)){
            if ($stmt->execute()){
                while ($row = $stmt->fetch()) {
                    echo "<div class='form-group'>";
                    $str = '<input type="submit" class="btn btn_chatroom" name="chat_'.$row['chatroom_id'].'" value="'.$row['title'].'"';
                    echo $str;
                    echo "</div>";
                }
            }
        }
        echo "</div>";
        echo "</div>";
        echo "</div>";

        if($addingRoom) {
            echo "<div>";
            echo "<label>New Chatroom Name</label>";
            echo "<input type='text' name='newChatRoom' class='form-control w'></input>";
            echo "</div>";
            echo "<div class='form-group'>";
            echo "<input type='submit' class='btn btn_primary' name='submitNewRoom' value='Submit'>";
            echo "</div>";
        } else if ($isAdmin) {
            echo "<div class='form-group'>";
            echo "<input type='submit' class='btn btn_primary' name='addroom' value='Add Chatroom'>";
            echo "</div>";
        }
        ?>


        <div class="form-group">
            <input type="submit" class="btn btn_primary" name="home" value="HOME">
        </div>
    </form>
</div>
</body>
</html>



