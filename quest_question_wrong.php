<?php

// Include config file
require_once "connection.php";
session_start();
$log_id = $_SESSION['id'];
$cur_quest = $_SESSION['chosen_quest'];
// call procedure here
$sql_1 = "CALL quest_incr_attempt(".$log_id.", ".$cur_quest.", @new_attempts, @new_points);";
$sql_2 = "SELECT @new_attempts AS attempts, @new_points AS points;";


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['ok'])) {
        // Redirect user to welcome page
        header("location: quest_question.php");
    }
} else {
    if(($stmt_1 = $conn->prepare($sql_1)) && ($stmt_2 = $conn->prepare($sql_2))){
        if(($stmt_1->execute()) && ($stmt_2->execute())){
            if ($row = $stmt_2->fetch()) {
                $num_attempts = $row['attempts'];
                $points = $row['points'];
                if($num_attempts > 0){
                    // quest is already completed
                    $msg = "Oh well, it seems as though you have already completed this quest with: ".$points." points earned.";
                } else {
                    // quest not completed yet
                    $num_attempts *= -1;
                    $msg = "The max points you can earn now is: ".$points." points";
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
    <title>Wrong Answer</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Oops! Wrong Answer</h2>
    <p> The answer you chose is incorrect</p>
    <p><?php echo $msg ; ?></p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="ok" value="OK">
            </div>
        </form>
</div>
</body>
</html>