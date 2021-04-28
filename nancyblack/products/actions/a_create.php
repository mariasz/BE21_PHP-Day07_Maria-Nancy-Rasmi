<?php

session_start();

if (isset($_SESSION[ 'user']) != "") {
   header("Location: ../../home.php");
   exit;
}

if  (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
   header("Location: ../../index.php" );
    exit;
}

require_once '../../components/db_connect.php' ;
require_once '../../components/file_upload.php';


if ($_POST) {  
   $name = $_POST['name'];
   // thats post method - what comes from the form - not from the database! make sure in the form at 
   //create.php
   $content = $_POST['content'];
   $hotel =$_POST['hotel'];
   $price =$_POST['price'];
   $uploadError = '';
   //this function exists in the service file upload.
   $picture = file_upload($_FILES['picture'],'product');  // if you dont have the file upload in line 3 wont work
   //this picture does not refer to the ordner calles "pictures" - but to the line 30 in create.php called
   //"picture"

   if($hotel == 'none'){
    //checks if the supplier is undefined and insert null in the DB
     $sql = "INSERT INTO offers (name, content, price, picture) 
     VALUES ('$name', '$content', '$price', '$picture->fileName', null)";
    }else{
     $sql = "INSERT INTO offers (name, content, price, picture) 
     VALUES ('$name', '$content', '$price', '$picture->fileName')";
   }

   if ($connect->query($sql) === true ) { //you put query inside ===true
       $class = "success"; //below how to show succes of conecction or not
       $message = "The entry below was successfully created <br>
            <table class='table w-50'><tr>
            <td> $name </td>
            <td> $price </td>
            </tr></table><hr>";
       $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';
   } else {
       $class = "danger";
       $message = "Error while creating record. Try again: <br>" . $connect->error;
       $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';
   }
   $connect->close();
} else {
   header("location: ../error.php");
}
?>



<!DOCTYPE html>
<html lang= "en">
   <head>
       <meta  charset="UTF-8">
       <title>Update</title>
       <?php require_once '../../components/boot.php' ?> 
   </head>
   <body>
       <div  class="container">
           <div class="mt-3 mb-3" >
               <h1>Create request response</h1>
           </div>
            <div class="alert alert-<?=$class;?>" role="alert">
               <p><?php echo ($message) ?? ''; ?></p> 
                <p><?php echo ($uploadError) ?? ''; ?></p>
                <a href='../index.php'><button class="btn btn-primary"  type='button'>Home</button ></a>
           </div >
       </div>
   </body>
</html>

<!-- look at line 53 - id ir ia nor null it prints what comes after interrogation -??- this sign