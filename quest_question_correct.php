<?php

// Include config file
require_once "connection.php";
session_start();
$log_id = $_SESSION['id'];
$cur_quest = $_SESSION['chosen_quest'];


if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
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
    <h2>Yay! Correct Answer</h2>
    <p> The answer you chose was correct</p>
    <p> You have earned: (max points)</p>

        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="home" value="OK, Go to Dashboard">
            </div>

        </form>
</div>
</body>
</html>
