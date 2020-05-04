<?php

// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

$u_log = $_SESSION['id'];
$username = $_SESSION['username'];

$q_name_err = $q_name = "";
$q_desc_err = $q_desc  = "";
$q_level_err = $q_level = "";
$q_points_err = $q_points = "";
$q_question_err = $q_question = "";
$q_answer_a_err = $q_answer_a = "";
$q_answer_b_err = $q_answer_b = "";
$q_answer_c_err = $q_answer_c = "";
$q_answer_d_err = $q_answer_d = "";
$q_correct_err = $q_correct = "";




if($_SERVER["REQUEST_METHOD"] == "POST"){

    if(isset($_POST['Cancel'])) {
        session_destroy();
        header("location: login.php");
        exit();
    }




    if(isset($_POST['submit'])) {
        if (empty(trim($_POST["title"]))) {
            $q_name_err = "Invalid Entry.";
        } else {
            $q_name = trim($_POST["title"]);
        }
        if (empty(trim($_POST["desc"]))) {
            $q_desc_err = "Invalid Entry.";
        } else {
            $q_desc = trim($_POST["desc"]);
        }if (empty(trim($_POST["level"]))) {
            $q_level_err = "Invalid Entry.";
        } else {
            $q_level = trim($_POST["level"]);
        }
        if (empty(trim($_POST["points"]))) {
            $q_points_err = "Invalid Entry.";
        } else {
            $q_points = trim($_POST["points"]);
        }
        if (empty(trim($_POST["question"]))) {
            $q_question_err = "Invalid Entry.";
        } else {
            $q_question = trim($_POST["question"]);
        }
        if (empty(trim($_POST["answer_a"]))) {
            $q_answer_a_err = "Invalid Entry.";
        } else {
            $q_answer_a = trim($_POST["answer_a"]);
        }
        if (empty(trim($_POST["answer_b"]))) {
            $q_answer_b_err = "Invalid Entry.";
        } else {
            $q_answer_b = trim($_POST["answer_b"]);
        }
        if (empty(trim($_POST["answer_c"]))) {
            $q_answer_c_err = "Invalid Entry.";
        } else {
            $q_answer_c = trim($_POST["answer_c"]);
        }
        if (empty(trim($_POST["answer_d"]))) {
            $q_answer_d_err = "Invalid Entry.";
        } else {
            $q_answer_d = trim($_POST["answer_d"]);
        }
        if (empty(trim($_POST["correct"]))) {
            $q_correct_err = "Invalid Entry.";
        } else {
            $q_correct = trim($_POST["correct"]);
        }


//        $filename = $_POST["fileToUpload"]["name"];
        $filename = "Not_a_real_filename.jpeg";
        echo "<p>$filename</p>";
        $imagePath = "image/" . $filename;
        echo "<p>$imagePath</p>";
        $check = $q_name_err
            . $q_desc_err
            . $q_level_err
            . $q_points_err
            . $q_question_err
            . $q_answer_a_err
            . $q_answer_b_err
            . $q_answer_c_err
            . $q_answer_d_err
            . $q_correct_err;


        if (empty($check)) {
            // Prepare an insert statement
            $u_id = 0;
            if ($stmt = $conn->prepare("SELECT user_id FROM tbl_login a INNER JOIN tbl_user USING(login_id) WHERE login_id = " . $u_log)) {
                if ($stmt->execute()) {
                    if ($row = $stmt->fetch()) {
                        $u_id = $row['user_id'];
                    }
                }
            }

            $sql = "INSERT INTO tbl_question(question, answer_1, answer_2, answer_3, answer_4, correct)
                    VALUES (:question, :answer_1, :answer_2, :answer_3, :answer_4, :correct);
                    INSERT INTO tbl_quests(quest_name, description, point_value,difficulty_level, question_id, image_a, added_by)
                    VALUES (:quest_name, :description, :point_value,:difficulty_level, LAST_INSERT_ID(), :image_a, :added_by);";

//            $rnd_2 = 'INSERT INTO tbl_question(question, answer_1, answer_2, answer_3, answer_4, correct)
//                        VALUES ("'.$q_question.'", "'.$q_answer_a.'", "'.$q_answer_b.'", "'.$q_answer_c.'", "'.$q_answer_d.'", '.$q_correct.');
//                        INSERT INTO tbl_quests(quest_name, description, point_value,difficulty_level, question_id, image_a, added_by)
//                        VALUES ("'.$q_name.'", "'.$q_desc.'", '.$q_points.', '.$q_level.', LAST_INSERT_ID(), "'.$imagePath.'", '.$u_id.');';

            if ($stmt = $conn->prepare($sql)) {

                // Bind variables to the prepared statement as parameters
                $stmt->bindValue(":quest_name", $q_name, PDO::PARAM_STR);
                $stmt->bindParam(":description", $q_desc, PDO::PARAM_STR);
                $stmt->bindParam(":point_value", $q_points, PDO::PARAM_INT);
                $stmt->bindParam(":difficulty_level", $q_level, PDO::PARAM_INT);
                $stmt->bindParam(":image_a", $imagePath, PDO::PARAM_STR);
                $stmt->bindParam(":added_by", $u_id, PDO::PARAM_INT);
                $stmt->bindParam(":question", $q_question, PDO::PARAM_STR);
                $stmt->bindParam(":answer_1", $q_answer_a, PDO::PARAM_STR);
                $stmt->bindParam(":answer_2", $q_answer_b, PDO::PARAM_STR);
                $stmt->bindParam(":answer_3", $q_answer_c, PDO::PARAM_STR);
                $stmt->bindParam(":answer_4", $q_answer_d, PDO::PARAM_STR);
                $stmt->bindParam(":correct", $q_correct, PDO::PARAM_INT);


                // Attempt to execute the prepared statement
                if ($stmt->execute()) {
                    // Redirect to login page
//                    header("location: upload.php");
                    header("location: admin_all_quests.php");
                } else {
                    echo "Something went wrong. Please try again later.";
                }

                // Close statement
                unset($stmt);
            }
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New User</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>New Quest</h2>
    <p>Please fill in the information for the new quest.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data" >
        <div class="form-group <?php echo (!empty($q_name_err)) ? 'has-error' : ''; ?>">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
            <span class="help-block"><?php echo $q_name_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_desc_err)) ? 'has-error' : ''; ?>">
            <label>Description</label>
            <input type="text" name="desc" class="form-control">
            <span class="help-block"><?php echo $q_desc_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_level_err)) ? 'has-error' : ''; ?>">
            <label>Level</label>
            <input type="number" name="level" class="form-control">
            <span class="help-block"><?php echo $q_level_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_points_err)) ? 'has-error' : ''; ?>">
            <label>Points</label>
            <input type="number" name="points" class="form-control">
            <span class="help-block"><?php echo $q_points_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_question_err)) ? 'has-error' : ''; ?>">
            <label>Question</label>
            <input type="text" name="question" class="form-control">
            <span class="help-block"><?php echo $q_question_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_answer_a_err)) ? 'has-error' : ''; ?>">
            <label>Answer A</label>
            <input type="text" name="answer_a" class="form-control">
            <span class="help-block"><?php echo $q_answer_a_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_answer_b_err)) ? 'has-error' : ''; ?>">
            <label>Answer B</label>
            <input type="text" name="answer_b" class="form-control">
            <span class="help-block"><?php echo $q_answer_b_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_answer_c_err)) ? 'has-error' : ''; ?>">
            <label>Answer C</label>
            <input type="text" name="answer_c" class="form-control">
            <span class="help-block"><?php echo $q_answer_c_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_answer_d_err)) ? 'has-error' : ''; ?>">
            <label>Answer D</label>
            <input type="text" name="answer_d" class="form-control">
            <span class="help-block"><?php echo $q_answer_d_err; ?></span>
        </div>

        <div class="form-group <?php echo (!empty($q_correct_err)) ? 'has-error' : ''; ?>">
            <label>Correct Answer</label>
            <input type="number" min="1" max="4" name="correct" class="form-control">
            <span class="help-block"><?php echo $q_correct_err; ?></span>
        </div>

        <input type="file" name="fileToUpload" id="fileToUpload">
<!--        <input type="submit" value="Upload Image" name="submit">-->
        <p></p>

        <div class="form-group">
            <input type="submit" name="submit" class="btn btn-primary" value="Submit">
        </div>

        <div class="form-group">
            <input type="submit" name="Cancel" class="btn btn-primary" value="Cancel">
        </div>

    </form>
</div>
</body>
</html>
