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
   $name = $_POST['name']; //this comes from form in post method names of input fields 
   $price = $_POST['price'];
   $supplier = $POST['supplier'];
   $id = $_POST['id'];
   //variable for upload pictures errors is initialized
   $uploadError = '';

   $picture = file_upload($_FILES['picture']);//file_upload() called  
   if ($picture->error===0){
       ($_POST["picture"]=="product.png")?: unlink("../pictures/$_POST[picture]"); 
       //this line is important
       //this elvis operator is only looking for false :) this is a trick 
       //if name is different than product.png it takes it from database 
       //it triggers deleting the pic      its only fine in this case for picture not name or price
       //define to the database that this colum can do this preciesly problems appear
       $sql = "UPDATE products SET name = '$name', 
       price = $price, 
       picture = '$picture->fileName', 
       fk_supplierId = $supplier  
       WHERE id = {$id}";

   }else{
       $sql = "UPDATE products SET name = '$name', 
       price = $price,
       fk_supplierId = $supplier
       WHERE id = {$id}";
   }    
   if ($connect->query($sql) === TRUE) {
       $class = "success";
       $message = "The record was successfully updated";
       $uploadError = ($picture->error !=0)? $picture->ErrorMessage :'';
   } else {
       $class = "danger";
       $message = "Error while updating record : <br>" . $connect->error;
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
               <h1>Update request response</h1>
           </div>
            <div class="alert alert-<?php echo $class;?>" role="alert">
               <p><?php echo ($message) ?? ''; ?></p>
                <p><?php echo ($uploadError) ?? ''; ?></p>
                <a href='../update.php?id=<?=$id;?>' ><button class="btn btn-warning" type='button'>Back</button></a>
                <a href='../index.php'><button class="btn btn-success"  type='button'>Home</button></a>
            </div>
       </div >
   </body>
</html>
