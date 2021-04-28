<?php
session_start();
require_once 'components/db_connect.php';

// it will never let you open index(login) page if session is set
if (isset($_SESSION['user']) != "") {
    header("Location: home.php");
    exit;
}
if (isset($_SESSION['adm']) != "") {
    header("Location: dashboard.php"); // redirects to home.php
}

$error = false;
$email = $password = $emailError = $passwordError = '';

if (isset($_POST['btn-login'])) {

    // prevent sql injections/ clear user invalid inputs
    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);
    // prevent sql injections / clear user invalid inputs

    if (empty($email)) {
        $error = true;
        $emailError = "Please enter your email address.";
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    }

    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter your password.";
    }

    // if there's no error, continue to login
    if (!$error) {

        $password = hash('sha256', $password); // password hashing

        $sqlSelect = "SELECT id, first_name, password, status FROM user WHERE email = ? ";
        $stmt = $connect->prepare($sqlSelect);
        $stmt->bind_param("s", $email);
        $work = $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $count = $result->num_rows;
        if ($count == 1 && $row['password'] == $password) {
            if($row['status'] == 'adm'){
                $_SESSION['adm'] = $row['id'];           
                header( "Location: dashboard.php");}
            else{
                $_SESSION['user'] = $row['id']; 
               header( "Location: home.php");
            }          
        } else {
            $errMSG = "Incorrect Credentials, Try again...";
        }
    }
}
$connect->close();
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login & Registration System</title>
        <?php require_once 'components/boot.php'?>
    </head>
    <body>
        <div class="container">
            <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" autocomplete="off">
                <h2>LogIn</h2>
                <hr/>
                <?php
                if (isset($errMSG)) {
                    echo $errMSG;
                }
                ?>
        
                <input type="email" autocomplete="off" name="email" class="form-control" placeholder="Your Email" value="<?php echo $email; ?>"  maxlength="40" />
                <span class="text-danger"><?php echo $emailError; ?></span>

                <input type="password" name="password"  class="form-control" placeholder="Your Password" maxlength="15"  />
                <span class="text-danger"><?php echo $passwordError; ?></span>
                <hr/>
                <button button class="btn btn-block btn-primary" type="submit" name="btn-login">Sign In</button>
                <hr/>
                <a href="register.php">Not registered yet? Click here</a>
            </form>
        </div>
</body >
</html>
    </body>
</html>
