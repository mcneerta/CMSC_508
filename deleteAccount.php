<?php
require_once "connection.php";
session_start();

if($_SERVER["REQUEST_METHOD"] == "POST"){
    if(isset($_POST['Confirm'])) {
        $sql = "DELETE FROM tbl_login  WHERE login_id = :id";

        if($stmt = $conn->prepare($sql)) {
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":id", $param_id, PDO::PARAM_INT);

            $param_id = $_SESSION["id"];
            // Attempt to execute the prepared statement
            if ($stmt->execute()) {
                // Password updated successfully. Destroy the session, and redirect to login page
                session_destroy();
                header("location: register.php");
                exit();
            } else {
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
    }
    if(isset($_POST['Cancel'])) {
        header("location: login_redirect.php");
    }
}
?>

<html lang="en">
<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>Delete Account</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 450px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Delete Account</h2>
    <p>This action cannot be undone! Are you sure you would like to delete your account?</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group">
            <input type="submit" name="Confirm" class="btn btn-primary" value="Confirm">
        </div>
        <div class="form-group">
            <input type="submit" name="Cancel" class="btn btn-primary" value="Cancel">
        </div>
    </form>
</div>
</body>
</html>
