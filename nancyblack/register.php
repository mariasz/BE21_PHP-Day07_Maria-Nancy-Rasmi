<?php
session_start(); // start a new session or continues the previous
if (isset($_SESSION['user']) != "") {
    header("Location: home.php"); // redirects to home.php
}
if (isset($_SESSION['adm']) != "") {
    header("Location: dashboard.php"); // redirects to home.php
}
require_once 'components/db_connect.php';
require_once 'components/file_upload.php';
$error = false;
$first_name = $last_name = $password = $date_of_birth = $email = $picture = '';
$first_nameError = $last_nameError = $passwordError = $date_of_birthError = $emailError = $pictureError = '';
if (isset($_POST['btn-signup'])) {

    // sanitize user input to prevent sql injection
    $first_name = trim($_POST['first_name']);
    //trim - strips whitespace (or other characters) from the beginning and end of a string
    $first_name = strip_tags($first_name);
    // strip_tags -- strips HTML and PHP tags from a string
    $first_name = htmlspecialchars($first_name);
    // htmlspecialchars converts special characters to HTML entities
    
    $last_name = trim($_POST['last_name']);
    $last_name = strip_tags($last_name);
    $last_name = htmlspecialchars($last_name);        

    $password = trim($_POST['password']);
    $password = strip_tags($password);
    $password = htmlspecialchars($password);

    $date_of_birth = trim($_POST['date_of_birth']);
    $date_of_birth = strip_tags($date_of_birth);
    $date_of_birth = htmlspecialchars($date_of_birth);

    $email = trim($_POST['email']);
    $email = strip_tags($email);
    $email = htmlspecialchars($email);

    //we dont include the status here it only belongs to user//

    $uploadError = '';
    $picture = file_upload($_FILES['picture']);

    // basic name validation
    if (empty($first_name) || empty($last_name)) {
        $error = true;
        $fnameError = "Please enter your full name and surname";
    } else if (strlen($first_name) < 3 || strlen($last_name) < 3) {
        $error = true;
        $first_nameError = "Name and surname must have at least 3 characters.";
    } else if (!preg_match("/^[a-zA-Z]+$/", $first_name) || !preg_match("/^[a-zA-Z]+$/", $last_name)) {
        $error = true;
        $firstnameError = "Name and surname must contain only letters and no spaces.";
    }
   
    //basic email validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = true;
        $emailError = "Please enter valid email address.";
    } else {
    // checks whether the email exists or not
        $query = "SELECT email FROM user WHERE email='$email'";
        $result = mysqli_query($connect, $query);
        $count = mysqli_num_rows($result);
        if ($count != 0) {
            $error = true;
            $emailError = "Provided Email is already in use.";
        }
    }
    //checks if the date input was left empty
    if (empty($date_of_birth)) {
        $error = true;
        $date_of_birthError = "Please enter your date of birth.";
    }
    // password validation
    if (empty($password)) {
        $error = true;
        $passwordError = "Please enter password.";
    } else if (strlen($password) < 6) {
        $error = true;
        $passwordError = "Password must have at least 6 characters.";
    }

    // password hashing for security
    $password = hash('sha256', $password);
    // if there's no error, continue to signup
    if (!$error) {

        $query = "INSERT INTO user(first_name, last_name, password, date_of_birth, email, picture) 
        VALUES('$first_name', '$last_name', '$password', '$date_of_birth', '$email', '$picture->fileName')";
        $res = mysqli_query($connect, $query);

        if ($res) {
            $errTyp = "success";
            $errMSG = "Successfully registered, you may login now";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';

        } else {
            $errTyp = "danger";
            $errMSG = "Something went wrong, try again later...";
            $uploadError = ($picture->error != 0) ? $picture->ErrorMessage : '';
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
            <form class="w-75" method="post" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>"  
            autocomplete="off" enctype="multipart/form-data">
                <h2>Sign Up.</h2>
                <hr/>
                <?php
                if (isset($errMSG)) {
                ?>
                    <div class="alert alert-<?php echo $errTyp ?>" >
                        <p><?php echo $errMSG; ?></p>
                        <p><?php echo $uploadError; ?></p>
                    </div>
                <?php
                }
                ?>

                <input type ="text"  name="first_name"  class="form-control"  placeholder="First name" maxlength="50" value="<?php echo $first_name ?>" />
                <span class="text-danger"> <?php echo $first_nameError; ?> </span>

                <input type ="text"  name="last_name"  class="form-control"  placeholder="Last name" maxlength="50" value="<?php echo $last_name ?>" />
                <span class="text-danger"> <?php echo $last_nameError; ?> </span>

                <input type="email" name="email" class="form-control" placeholder="Enter Your Email" maxlength="40" value ="<?php echo $email ?>"  />
                <span  class="text-danger"> <?php echo $emailError; ?> </span>
                <div class="d-flex">

                    <input class='form-control w-50' type="date"  name="date_of_birth" value ="<?php echo $date_of_birth ?>"/>
                    <span class="text-danger"> <?php echo $date_of_birthError; ?> </span>

                    <input class='form-control w-50' type="file" name="picture" >
                    <span class="text-danger"> <?php echo $pictureError; ?> </span>
                </div>
                <input type="password" name="password" class="form-control" placeholder="Enter Password" maxlength="15" /></span>
                <span class="text-danger"> <?php echo $passwordError; ?> </span>
                <hr/>

                <button type="submit" class="btn btn-block btn-primary" name="btn-signup">Sign Up</button>
                <hr/>
                <a href="index.php">You have no account yet? Sign in HERE!</a>
            </form>
        </div>
    </body>
</html>