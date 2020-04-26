<?php
// Include config file
require_once "connection.php";
session_start();
$log_id = $_SESSION['id'];
$cur_quest = $_SESSION['chosen_quest'];

if($_SERVER["REQUEST_METHOD"] != "POST") {

    $sql = "SELECT * , 
            (SELECT IFNULL(COUNT(*),0)  FROM tbl_completedquest a
                    WHERE a.quest_id = ".$cur_quest.") AS done_by
            FROM tbl_quests b WHERE b.quest_id = ".$cur_quest." ;";


    if ($stmt = $conn->prepare($sql)){
//        $stmt->bindValue(':login_id', $log_id);
        if($stmt->execute()){
            if ($row = $stmt->fetch()){
                $q_id = $row['quest_id'];
                $q_name = $row['quest_name'];
                $q_desc = $row['description'];
                $q_pts = $row['point_value'];
                $q_lvl = $row['difficulty_level'];
                $q_img = $row['image_a'];
                $q_question_id = $row['question_id'];
                $done_by = $row['done_by'];
            }
        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['cancel'])){
        header("location: user_avail_quests.php");
    }
    if(isset($_POST['answer'])){
        header("location: quest_question.php");
    }
}


?>



<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Quest Question</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2><?php echo $q_name; ?></h2>
    <img src="<?php echo $q_img; ?>" alt="<?php echo $q_img; ?>">
    <p> Description :<?php echo $q_desc; ?></p>
    <p> Level: <?php echo $q_lvl; ?></p>
    <p> Max Points: <?php echo $q_pts; ?></p>
    <p> Completed by: <?php echo $done_by ; ?></p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" name="cancel" class="btn btn-primary" value="Cancel">
            </div>
            <div class="form-group">
                <input type="submit" name="answer" class="btn btn-primary" value="Quiz Me!">
            </div>
        </form>
</div>
</body>
</html>