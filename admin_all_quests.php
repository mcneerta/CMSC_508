<?php
// Include config file
require_once "connection.php";
session_start();

// Check if the user is logged in, otherwise redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}


$sql = "SELECT quest_id AS q_id,
       quest_name AS q_name,
       difficulty_level as q_level
            FROM tbl_quests 
            ORDER BY q_level ASC , q_name ASC;";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['questref'])) {
        $_SESSION['chosen_quest'] = $_POST['questref'];
        header("location: admin_quest_selection.php");
    }

    if (isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
    }

    if (isset($_POST['add'])) {
        // Redirect user to welcome page
        header("location: admin_add_quest.php");
    }

}

?>


<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>All Quests</title>
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
        .container {
            /*text-align:center*/
            background-color: Transparent;
            background-repeat:no-repeat;
            border: none;
            cursor:pointer;
            overflow: hidden;
            outline:none;
            width: 200px;
            text-align: center;

        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>All Quests</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">


        <table border="1">
            <tr>
                <th>Level</th>
                <th style="align-content: center">Quest Name</th>
            </tr>
        <?php
        $prev = 0;
        if ($stmt = $conn->prepare($sql)){
            $stmt->bindValue(':login_id', $_SESSION["id"]);
//                    $stmt->bindValue(':login_id', 1);
            if ($stmt->execute()){
//                        $counter = 0;
                while ($row = $stmt->fetch()) {
                    if ($prev != $row['q_level']){
                        if ($prev != 0){
                            echo "</table>";
                            echo "</td>";
                        }
                        echo "<tr>";
                        echo "<td align='center'>".$row['q_level']."</td>";
                        echo "<td><table>";
                        $prev = $row['q_level'];
                        }
                    echo "<tr>";
                    echo "<td align='center'>";
                    echo "<button class='container' type='submit' name='questref' value='".$row['q_id']."' >";
                    echo "<p>".$row['q_name']."</p>";
                    echo "</button>";
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }
        ?>
        </table>
        </table>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="add" value="Add Quest">
        </div>
        <div class="form-group">
            <input type="submit" class="btn btn-primary" name="home" value="Home">
        </div>
    </form>
</div>
</body>
</html>

