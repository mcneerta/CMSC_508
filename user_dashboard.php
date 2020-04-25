<?php
// Include config file
require_once "connection.php";
session_start();


if($_SERVER["REQUEST_METHOD"] != "POST") {

    $sql = "
    Select a.user_id as user_id, b.level AS u_level, 
    IFNULL((SELECT SUM(z.points_earned)  FROM tbl_completedquest z WHERE z.player_id = user_id), 0) AS total_points,
        ((SELECT COUNT(*) FROM tbl_completedquest c INNER JOIN tbl_quests d WHERE c.player_id = user_id AND d.difficulty_level = u_level)
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

        }
    }


}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['userprofile'])) {
        echo "Go To User Profile";
    }
    if(isset($_POST['leaderBoard'])) {
        echo "Go To Leader Board";
        // Redirect user to welcome page
        header("location: user_leaderboard.php");
    }
    if(isset($_POST['avail_quests'])) {
        echo "Go To Available Quests";
    }
    if(isset($_POST['comp_quests'])) {
        echo "Go To Completed Quests";
    }
    if(isset($_POST['chatrooms'])) {
        echo "Go To Chatrooms";
    }
    if(isset($_POST['logout'])) {
        echo "This will be a Logout";
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
    <p> Percentage Complete : <?php echo $percent_complete ?></p>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="userprofile" value="Go To User Profile">
        </div>

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
            <input type="submit" class="btn btn-primary" name="logout" value="Log out">
        </div>
    </form>




