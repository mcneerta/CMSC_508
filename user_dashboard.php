<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$user_id = 0;

if($_SERVER["REQUEST_METHOD"] != "POST") {

    $sql = "
    Select a.user_id as user_id, b.level AS u_level, 
    IFNULL((SELECT SUM(z.points_earned)  FROM tbl_completedquest z WHERE z.player_id = user_id), 0) AS total_points,
        ((SELECT COUNT(*) FROM tbl_completedquest c INNER JOIN tbl_quests d USING(quest_id) WHERE c.player_id = user_id AND d.difficulty_level = u_level)
        /
        (SELECT COUNT(*) FROM tbl_quests e WHERE e.difficulty_level = u_level ))
    AS percent_complete
    FROM 
    tbl_user a
    INNER JOIN tbl_player b
    ON a.user_id = b.player_id
    WHERE a.login_id = :login_id;
    ";

    if ($stmt = $conn->prepare($sql)){
        $stmt->bindValue(':login_id', $_SESSION["id"]);

        if ($stmt->execute()){

            $row = $stmt->fetch();
            $username = $_SESSION['username'];
            $user_id = $row['user_id'];
            $level = $row['u_level'];
            $total_points = $row['total_points'];
            $percent_complete = $row['percent_complete'];
            $percent_complete = round($percent_complete * 100);

        }
    }


}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['userprofile'])) {
        header("location: editAccount.php");
    }
    if(isset($_POST['leaderBoard'])) {
        header("location: user_leaderboard.php");
    }
    if(isset($_POST['avail_quests'])) {
        header("location: user_avail_quests.php");
    }
    if(isset($_POST['comp_quests'])) {
        header("location: user_compl_quests.php");
    }
    if(isset($_POST['chatrooms'])) {
        header("location: chatrooms.php");
    }
    if(isset($_POST['level_up'])) {
        if($percent_complete = 100){
            $sql1 = "update tbl_player set level = level + 1 where player_id = $user_id";
            $sql2 = "
    Select a.user_id as user_id, b.level AS u_level, 
    IFNULL((SELECT SUM(z.points_earned)  FROM tbl_completedquest z WHERE z.player_id = user_id), 0) AS total_points,
        ((SELECT COUNT(*) FROM tbl_completedquest c INNER JOIN tbl_quests d USING(quest_id) WHERE c.player_id = user_id AND d.difficulty_level = u_level)
        /
        (SELECT COUNT(*) FROM tbl_quests e WHERE e.difficulty_level = u_level ))
    AS percent_complete
    FROM 
    tbl_user a
    INNER JOIN tbl_player b
    ON a.user_id = b.player_id
    WHERE a.login_id = :login_id;
    ";
            if(($stmt_1 = $conn->prepare($sql1)) && ($stmt_2 = $conn->prepare($sql2))){
                $stmt_2->bindValue(':login_id', $_SESSION["id"]);
                if ($stmt_2->execute()){

                    $row = $stmt_2->fetch();
                    $username = $_SESSION['username'];
                    $user_id = $row['user_id'];
                    $level = $row['u_level'];
                    $total_points = $row['total_points'];
                    $percent_complete = $row['percent_complete'];
                    $percent_complete = round($percent_complete * 100);

                }
                $stmt_1->execute();

            }
        }

        else{
            echo "You must complete all quests for your current level before leveling up.";
        }

    }
    if(isset($_POST['logout'])) {
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
    <h2>User Dashboard</h2>

    <p> Username: <?php echo $username ?> </p>
    <p> Current Points: <?php echo $total_points ?></p>
    <p> Level: <?php echo $level ?></p>
    <p> Percentage Complete : <?php echo $percent_complete ?>%</p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="leaderBoard" value="Go To Leader Board">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="avail_quests" value="See Available Quests">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="comp_quests" value="See Completed Quests">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="chatrooms" value="Go Chat Rooms">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="userprofile" value="Update User Info">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="level_up" value="Level Up">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="logout" value="Log out">
        </div>

        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="delete_acc" value="Delete Account">
        </div>
    </form>




