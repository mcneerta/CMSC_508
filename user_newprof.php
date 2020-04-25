<?php

// Include config file
require_once "connection.php";
session_start();


$username = $_SESSION['username'];
$login_id = $_SESSION['id'];
$first_name_err = $first_name = "";
$last_name_err = $last_name = "";
$email_err = $email = "";
$phone_err = $phone = "";


if($_SERVER["REQUEST_METHOD"] == "POST"){

    // Check if firstname is empty
    if(empty(trim($_POST["firstName"]))){
        $first_name_err = "Invalid Entry.";
    } else{
        $first_name = trim($_POST["firstName"]);
    }
    // Check if lastName is empty
    if(empty(trim($_POST["lastName"]))){
        $last_name_err = "Invalid Entry.";
    } else{
        $last_name = trim($_POST["lasttName"]);
    }
    // Check if email is empty
    if(empty(trim($_POST["email"]))){
        $email_err = "Invalid Entry.";
    } else{
        $email = trim($_POST["email"]);
    }
    // Check if phone is empty
    if(empty(trim($_POST["phone"]))){
        $phone = "";
    } else{
        $phone = trim($_POST["phone"]);
    }

    if(empty($first_name_err) && empty($last_name_err) && empty($email_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO tbl_user (login_id, first_name, last_name, email, phone, last_login)
                        VALUES (:login_id, :first_name, :last_name, :email, :phone, NOW());
                SELECT LAST_INSERT_ID() INTO @u_id;
                 INSERT INTO tbl_player(player_id)
                        VALUES (@u_id)";

        if($stmt = $conn->prepare($sql)){

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":login_id", $login_id, PDO::PARAM_STR);
            $stmt->bindParam(":first_name", $last_name, PDO::PARAM_STR);
            $stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
            $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);

            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Redirect to login page
                header("location: login_redirect.php");
            } else{
                echo "Something went wrong. Please try again later.";
            }

            // Close statement
            unset($stmt);
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
    <h2>New User</h2>
    <p>Please fill in your profile information.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
            <label>First Name</label>
            <input type="text" name="firstName" class="form-control">
            <span class="help-block"><?php echo $first_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
            <label>Last Name</label>
            <input type="text" name="lastName" class="form-control">
            <span class="help-block"><?php echo $last_name_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
            <span class="help-block"><?php echo $email_err; ?></span>
        </div>
        <div class="form-group <?php echo (!empty($phone_err)) ? 'has-error' : ''; ?>">
            <label>Phone</label>
            <input type="tel" name="phone" class="form-control">
            <span class="help-block"><?php echo $phone_err; ?></span>
        </div>
        <div class="form-group">
            <input type="submit" name="Login" class="btn btn-primary" value="Submit">
        </div>
        <div class="form-group">
            <input type="submit" name="Cancel" class="btn btn-primary" value="Cancel">
        </div>
    </form>
</div>
</body>
</html>
