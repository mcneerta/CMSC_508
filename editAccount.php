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
$sql = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['Cancel'])) {
        header("location: login_redirect.php");
    }

    if (isset($_POST['Reset'])) {
        header("location: resetPassword.php");
    }

    // Check if firstname is empty
    if (empty(trim($_POST["firstName"]))) {
        $first_name_err = "1";
    } else {
        $first_name = trim($_POST["firstName"]);
        $sql = $sql . "UPDATE tbl_user SET first_name = :first_name
                where login_id = :login_id;";
    }
    // Check if lastName is empty
    if (empty(trim($_POST["lastName"]))) {
        $last_name_err = "1";
    } else {
        $last_name = trim($_POST["lastName"]);
        $sql = $sql . "UPDATE tbl_user SET last_name = :last_name
                where login_id = :login_id;";
    }
    // Check if email is empty
    if (empty(trim($_POST["email"]))) {
        $email_err = "1";
    } else {
        $email = trim($_POST["email"]);
        $sql = $sql . "UPDATE tbl_user SET email = :email
                where login_id = :login_id;";
    }
    // Check if phone is empty
    if (empty(trim($_POST["phone"]))) {
        $phone_err = "All fields cannot be empty.";
    } else {
        $phone = trim($_POST["phone"]);
        $sql = $sql . "UPDATE tbl_user SET phone = :phone
                where login_id = :login_id;";
    }

    if(empty($first_name_err) || empty($last_name_err) || empty($email_err) || empty($phone_err)){

        if($stmt = $conn->prepare($sql)){

            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":login_id", $login_id, PDO::PARAM_STR);
            if(empty($first_name_err)) {
                $stmt->bindParam(":first_name", $first_name, PDO::PARAM_STR);
            }
            if(empty($last_name_err)) {
                $stmt->bindParam(":last_name", $last_name, PDO::PARAM_STR);
            }
            if(empty($email_err)) {
                $stmt->bindParam(":email", $email, PDO::PARAM_STR);
            }
            if(empty($phone_err)) {
                $stmt->bindParam(":phone", $phone, PDO::PARAM_STR);
            }

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
    <title>Update Information</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css">
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body>
<div class="wrapper">
    <h2>Update Information</h2>
    <p>Please fill in the fields you would like to change.</p>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="form-group <?php echo (!empty($first_name_err)) ? 'has-error' : ''; ?>">
            <label>First Name</label>
            <input type="text" name="firstName" class="form-control">
            <!-- <span class="help-block"><?php echo $first_name_err; ?></span> -->
        </div>
        <div class="form-group <?php echo (!empty($last_name_err)) ? 'has-error' : ''; ?>">
            <label>Last Name</label>
            <input type="text" name="lastName" class="form-control">
           <!-- <span class="help-block"><?php echo $last_name_err; ?></span> -->
        </div>
        <div class="form-group <?php echo (!empty($email_err)) ? 'has-error' : ''; ?>">
            <label>Email</label>
            <input type="email" name="email" class="form-control">
           <!-- <span class="help-block"><?php echo $email_err; ?></span> -->
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
            <input type="submit" name="Reset" class="btn btn-primary" value="Reset Password">
        </div>
        <div class="form-group">
            <input type="submit" name="Cancel" class="btn btn-primary" value="Cancel">
        </div>
    </form>
</div>
</body>
</html>

