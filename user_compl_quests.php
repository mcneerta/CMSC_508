<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$sql = "SELECT a.quest_id AS q_id, a.quest_name AS q_name, a.difficulty_level AS q_level, b.points_earned AS q_points
            FROM tbl_quests a
            INNER JOIN tbl_completedquest b
            INNER JOIN tbl_user c
            ON a.quest_id = b.quest_id
            AND b.player_id = c.user_id
            WHERE c.login_id = :login_id
            ORDER BY q_level, q_name;";


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
    <h2>Completed Quests</h2>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <table style="width:100%">
                <tr>
                    <th>Level</th>
                    <th>Quest Name</th>
                    <th>Points Earned</th>
                </tr>

                <?php
                if ($stmt = $conn->prepare($sql)){
                    $stmt->bindValue(':login_id', $_SESSION["id"]);
//                    $stmt->bindValue(':login_id', 1);
                    if ($stmt->execute()){
//                        $counter = 0;
                        while ($row = $stmt->fetch()) {
                            echo "<tr>";
                            echo "<th>".$row['q_level']."</th>";
                            echo "<th>".$row['q_name']."</th>";
                            echo "<th>".$row['q_points']."</th>";
                            echo "</tr>";
                            }
                    }
                }
                ?>
            </table>

            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="home" value="HOME">
            </div>
        </form>
</div>
</body>
</html>
