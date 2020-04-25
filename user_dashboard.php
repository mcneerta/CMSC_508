<?php
// Include config file
require_once "connection.php";
session_start();


// TODO Should probably have the logic of ADMIN vs Player in this file?


if($_SERVER["REQUEST_METHOD"] != "POST") {

    echo '<html lang="en">
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
    <h2>User Dashboard</h2>';



//    $stmt = $conn->prepare("Select user_id FROM user WHERE login_id = :login_id");
//    $stmt->bindValue(':login_id', $_SESSION["id"]);
//    $stmt->execute();
//    $row = $stmt->fetch();
//    $user_id = $row['user_id'];
//
//    $stmt = $conn->prepare("SELECT SUM(points) AS total_points FROM completed_quests WHERE player_id = :user_id");
//    $stmt->bindValue(':user_id', $user_id);
//    $stmt->execute();
//    $row = $stmt->fetch();
//    $total_points = $row['total_points'];
//
//    $stmt = $conn->prepare("STATEMENT TO CALCULATE PERCENTAGE COMPLETE");
//    $stmt->bindValue(':user_id', $user_id);
//    $stmt->execute();
//    $row = $stmt->fetch();
//    $percent_complete = $row['total_points'];


    echo "<p> Username: $_SESSION[username]</p>";


    echo "<p> Current Point: (User Points Here)</p>"; //$total_points
    echo "<p> Level: (User Level Here)</p>"; // $percent_complete
    echo "<p> Percentage Complete : (Percent complete for this level)</p>";



    $rr = htmlspecialchars($_SERVER["PHP_SELF"]);
    echo "<form action= $rr method='post'>";

    echo '
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
    ';

}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['userprofile'])) {
        echo "Go To User Profile";
    }
    if(isset($_POST['leaderBoard'])) {
        echo "Go To Leader Board";
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