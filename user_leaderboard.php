<?php
// Include config file
require_once "connection.php";
session_start();

if($_SERVER["REQUEST_METHOD"] != "POST") {


    // TODO currently rank is found by totaling counting all players that are at or above user points
    //  Ok for now, but if points are ever equivalent, the time the user earned the points may be needed as well
    $sql_rank = "Select COUNT(*) as ranking
FROM tbl_user a right JOIN tbl_player p
on a.user_id = p.player_id
WHERE (SELECT IFNULL(SUM(b.points_earned),0) FROM tbl_completedquest b WHERE b.player_id 
= (SELECT z.user_id FROM tbl_user z WHERE z.login_id = :login_id) )
            <=
            (SELECT IFNULL(SUM(c.points_earned),0) FROM tbl_completedquest c WHERE c.player_id 
                    = a.user_id);
    ";


    $sql_top10 = "Select c.username AS name, (SELECT IFNULL(SUM(f.points_earned),0) FROM tbl_completedquest f WHERE f.player_id = a.user_id) AS points
    FROM 
        tbl_user a
        INNER JOIN tbl_login c
        RIGHT JOIN tbl_player b
        ON a.user_id = b.player_id
         AND a.login_id = c.login_id
    WHERE (SELECT IFNULL(SUM(d.points_earned),0) FROM tbl_completedquest d WHERE d.player_id 
                    = (SELECT z.user_id FROM tbl_user z WHERE z.login_id = :login_id) )
            <=
            (SELECT IFNULL(SUM(e.points_earned),0) FROM tbl_completedquest e WHERE e.player_id 
                    = a.user_id)
    ORDER BY points DESC
    LIMIT 10;
    ";

    if ($stmt = $conn->prepare($sql_rank)){
        $stmt->bindValue(':login_id', $_SESSION["id"]);

        if ($stmt->execute()){
            
            $row = $stmt->fetch();
            $username = $_SESSION['username'];
            $user_rank = $row['ranking'];
        }
    }
}

if($_SERVER["REQUEST_METHOD"] == "POST") {

    // Redirect user to welcome page
    header("location: login_redirect.php");
}

?>


<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Leader Board</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
    body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
        }
        th, td {
            padding: 15px;
            text-align: left;
        }
        table#t01 {
            width: 100%;
            background-color: #f1f1c1;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Leaderboard</h2>
    <p> Your Ranking: <?php echo $user_rank ?></p>
    <p> The Board: </p>
    <table>
        <?php
        if ($stmt = $conn->prepare($sql_top10)){
            $stmt->bindValue(':login_id', $_SESSION["id"]);
            if ($stmt->execute()){
                $counter = 1;

                echo "<tr>";
                echo "<th>RANK</th>";
                echo "<th>USER</th>";
                echo "<th>POINTS</th>";
                echo "</tr>";

                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<th>$counter</th>";
                    echo "<th>$row[name]</th>";
                    echo "<th>$row[points]</th>";
                    echo "</tr>";
                    $counter += 1;
                }
            }
        }
        ?>
    </table>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">

    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="HOME">
    </div>

    </form>
</div>
</body>
</html>