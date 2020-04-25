<?php
// Include config file
require_once "connection.php";
session_start();

if($_SERVER["REQUEST_METHOD"] != "POST") {
    
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
    
}
if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['home'])) {
        // Redirect user to welcome page
        header("location: login_redirect.php");
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
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Available Quests</h2>


        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

            <ul>
                <?php
                if ($stmt = $conn->prepare($sql)){
                    $stmt->bindValue(':login_id', $_SESSION["id"]);
                    if ($stmt->execute()){
                        while ($row = $stmt->fetch()) {
                            echo "<li><a>$row[q_name]</a></li>";
                            }
                    }
                }
                ?>

            </ul>
            
            
            <div class="form-group">
                <input type="submit" class="btn btn-primary" name="home" value="HOME">
            </div>

        </form>
</div>
</body>
</html>
