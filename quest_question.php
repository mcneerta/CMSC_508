<?php

// Include config file
require_once "connection.php";
session_start();
$log_id = $_SESSION['id'];
$cur_quest = $_SESSION['chosen_quest'];

$sql_1 = "SELECT a.quest_name , a.point_value, a.image_a, b.question, b.answer_1, b.answer_2, b.answer_3, b.answer_4, b.correct 
            FROM 
                tbl_quests a INNER JOIN tbl_question b USING(question_id)
            WHERE
                a.quest_id = ".$cur_quest.";";

$sql_2 ="SELECT user_id, 
		 IFNULL((
            SELECT attempts
            FROM tbl_completedquest
            WHERE player_id = user_id AND quest_id = ".$cur_quest."
            ),0) AS attempts,
		 IFNULL((
            SELECT points_earned
            FROM tbl_completedquest
            WHERE player_id = user_id AND quest_id = ".$cur_quest."
            ),-1) AS points_earned
	FROM tbl_user 
	WHERE login_id = ".$log_id." ; ";



if(($stmt_1 = $conn->prepare($sql_1)) && ($stmt_2 = $conn->prepare($sql_2))){
    if(($stmt_1-> execute()) && ($stmt_2-> execute())){
        if(($row_1 = $stmt_1->fetch()) && ($row_2 = $stmt_2->fetch())){
            $q_name = $row_1['quest_name'];
            $q_pts = $row_1['point_value'];
            $q_img = $row_1['image_a'];
            $q_qst = $row_1['question'];
            $q_ans_1 = $row_1['answer_1'];
            $q_ans_2 = $row_1['answer_2'];
            $q_ans_3 = $row_1['answer_3'];
            $q_ans_4 = $row_1['answer_4'];
            $q_cor = $row_1['correct'];
            $u_id = $row_2['user_id'];
            $u_attempts = $row_2['attempts'];
            $u_cur_points = $row_2['points_earned'];
            if ($u_cur_points != 1){
                $u_points = $u_cur_points;
                $msg = "Quest Already Completed: ".$u_points." Points Earned";
            } else {
                $u_points = $q_pts - ((($u_attempts - 1) * ($q_pts) / 4));

                $msg = "Quest Not Completed: ".$u_points." Points Possible";
            }

        }
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['btn_01']) && $q_cor == 1){
        header("location: quest_question_correct.php");
    } elseif (isset($_POST['btn_02']) && $q_cor == 2){
        header("location: quest_question_correct.php");
    } elseif (isset($_POST['btn_03']) && $q_cor == 3){
        header("location: quest_question_correct.php");
    } elseif (isset($_POST['btn_04']) && $q_cor == 4){
        header("location: quest_question_correct.php");
    } else {
        header("location: quest_question_wrong.php");
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
    <p> <?php echo $msg; ?></p>
    <p> Question: <?php echo $q_qst; ?></p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <div class="form-group">
                <input type="submit" name="btn_01" class="btn btn-primary" value="<?php echo $q_ans_1; ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="btn_02" class="btn btn-primary" value="<?php echo $q_ans_2; ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="btn_03" class="btn btn-primary" value="<?php echo $q_ans_3; ?>">
            </div>
            <div class="form-group">
                <input type="submit" name="btn_04" class="btn btn-primary" value="<?php echo $q_ans_4; ?>">
            </div>

        </form>
</div>
</body>
</html>
