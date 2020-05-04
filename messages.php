<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$sql = "SELECT a.message, a.time_stamp, c.username, b.user_id
FROM tbl_messages a 
    INNER JOIN tbl_user b 
    INNER JOIN tbl_login c 
        ON a.contributor_id = b.user_id 
               AND b.login_id = c.login_id 
WHERE a.chatroom_id = :chosen_chatroom 
  AND a.visible > 0
ORDER BY a.time_stamp DESC ;";

if ($stmt = $conn->prepare("SELECT user_id FROM tbl_user WHERE login_id = :login_id ;")) {
    $stmt->bindValue(':login_id', $_SESSION['id']);
    if ($stmt->execute()) {
        if ($row = $stmt->fetch()) {
            $user_id = $row['user_id'];
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['goback'])) {
        // Redirect user to welcome page
        header("location: chatrooms.php");
    }

    if (isset($_POST['look_message'])) {
        echo "Clicked on message";
    }
    if (isset($_POST['submit'])) {
        if(!empty(trim($_POST["user_message"]))){
            $message = trim($_POST["user_message"]);
            $sql_message = "INSERT INTO tbl_messages(chatroom_id , time_stamp, message, contributor_id, visible) 
                                VALUES( :chatroom_id, NOW(), :message, :user_id, 1)";
            if ($stmt = $conn->prepare($sql_message)) {
                $stmt->bindParam(":user_id", $user_id, PDO::PARAM_STR);
                $stmt->bindParam(":chatroom_id", $_SESSION['chosen_chatroom'], PDO::PARAM_STR);
                $stmt->bindParam(":message", $message, PDO::PARAM_STR);
                if ($stmt->execute()) {
                    // message uploaded
                }
            }
        }
    }
}

if ($stmt = $conn->prepare("SELECT title FROM tbl_chatroom WHERE chatroom_id = :chosen_chatroom ;")) {
    $stmt->bindValue(':chosen_chatroom', $_SESSION["chosen_chatroom"]);
    if ($stmt->execute()) {
        if ($row = $stmt->fetch()) {
            $chatroom_name = $row['title'];
        }
    }
}


?>



<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Chatroom</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
        .container {
            /*padding: 10px;*/
            /*margin: 10px 0;*/
            width: 100%;
        }
        .container_message {
            border: 2px solid #dedede;
            background-color: #f1f1f1;
            border-radius: 5px;
            padding: 10px;
            margin: 10px 0;
            width: 80%;
        }
        .darker {
            border-color: #ccc;
            background-color: #ddd;
        }
        .right {
            text-align: right;
        }
        .con_right {
            float: right;
        }
        .username{
            font-style: italic;
            font-size: smaller;
        }
        .time-right{
            font-style: italic;
            font-size: xx-small;
            float: right;
        }
        .time-left{
            font-style: italic;
            font-size: xx-small;
        }
        .msg{
            width: 100%;
            /*height: 100%;*/
        }
    </style>

</head>
<body>
<div class="wrapper">
    <h2><?php echo $chatroom_name; ?></h2>

    <div class="pre-scrollable" >
            <?php
            if ($stmt = $conn->prepare($sql)){
                $stmt->bindValue(':chosen_chatroom', $_SESSION["chosen_chatroom"]);
                if ($stmt->execute()){
                    $counter = 0;
                    while ($row = $stmt->fetch()) {
                        if($row['user_id'] == $user_id){
                            echo "<tr>";
                            echo "<td align='center'>";
                            echo "<button class='container' type='submit' name='questref' value='' >";
                            echo "<p class='right username'>".$row['username']."</p>";
                            echo "<p class='right'>".$row['message']."</p>";
                            echo "<span class='time-right'>".$row['time_stamp']."</span>";
                            echo "</button>";
                            echo "</td>";
                            echo "</tr>";

                        } else {
                            echo "<tr>";
                            echo "<td align='center'>";
                            echo "<button class='container' type='submit' name='questref' value='' >";
                            echo "<p class='right username'>".$row['username']."</p>";
                            echo "<p class='right'>".$row['message']."</p>";
                            echo "<span class='time-left'>".$row['time_stamp']."</span>";
                            echo "</button>";
                            echo "</td>";
                            echo "</tr>";
                        }
                        $counter += 1;
                    }
                }
            }
            ?>
    </div>
    </form>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <label>Message</label>
            <input type="text" name="user_message" class="form-control">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="submit" value="Submit">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="goback" value="Go Back">
        </div>
    </form>

</div>
<script>
        function myclicktest(cb)
        {
          <?php// header("location: login_redirect.php");?>
        }
    </script>
</body>
</html>