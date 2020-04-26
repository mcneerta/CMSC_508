<?php
// Include config file
require_once "connection.php";
session_start();


$sql = "SELECT a.quest_id AS q_id, a.quest_name AS q_name
            FROM tbl_quests a
            WHERE a.difficulty_level = 
                (
                SELECT b.level 
                FROM tbl_player b LEFT JOIN tbl_user c 
                ON b.player_id = c.user_id
                WHERE c.login_id = :login_id
                )
            ORDER BY q_name ASC;";

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
    }

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bindValue(':login_id', $_SESSION["id"]);
        if ($stmt->execute()) {
            while ($row = $stmt->fetch()) {
                if(isset($_POST['quest_'.$row['q_id']])) {
                    $_SESSION['chosen_quest'] = $row['q_id'];
                    header("location: quest_selection.php");
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
    <title>Available Quests</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
        .btn-primary {
            color: #fff;
            background-color: #337ab7;
            border-color: #2e6da4;
            height: 50px;
            width: 200px;
            margin: 2px;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Available Quests</h2>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


                <?php
                if ($stmt = $conn->prepare($sql)){
                    $stmt->bindValue(':login_id', $_SESSION["id"]);
//                    $stmt->bindValue(':login_id', 1);
                    if ($stmt->execute()){
//                        $counter = 0;
                        while ($row = $stmt->fetch()) {

                            echo "<div class='form-group'>";
                            $str = '<input type="submit" class="btn btn-primary" name="quest_'.$row['q_id'].'" value="'.$row['q_name'].'"';
//                            echo "<input type='submit' class='btn btn-primary' name='quest_'".$row[q_id]." value='$row[q_name]'></input>";
                            echo $str;
                            echo "</div>";
//                            $choices[$counter] = $row['q_id'];
//                            $counter += 1;
                            }
                    }
                }
                ?>


            
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="home" value="HOME">
            </div>
        </form>
</div>
</body>
</html>
