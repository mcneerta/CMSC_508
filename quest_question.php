<?php

// Include config file
require_once "connection.php";
session_start();
$log_id = $_SESSION['id'];
$cur_quest = $_SESSION['chosen_quest'];

$sql = "SELECT a.quest_name , a.image_a, b.question, b.answer_1, b.answer_2, b.answer_3, b.answer_4, b.correct 
FROM 
    tbl_quests a INNER JOIN tbl_question b USING(question_id)
WHERE
    a.quest_id = ".$cur_quest.";";

if($stmt = $conn->prepare($sql)){
    if($stmt-> execute()){
        if($row = $stmt->fetch()){
            $q_name = $row['quest_name'];
            $q_img = $row['image_a'];
            $q_qst = $row['question'];
            $q_ans_1 = $row['answer_1'];
            $q_ans_2 = $row['answer_2'];
            $q_ans_3 = $row['answer_3'];
            $q_ans_4 = $row['answer_4'];
            $q_cor = $row['correct'];
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
