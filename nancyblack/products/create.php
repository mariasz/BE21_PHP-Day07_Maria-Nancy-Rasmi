<?php
session_start();
require_once '../components/db_connect.php';


if (isset($_SESSION['user']) != "" ) {
   header("Location: ../home.php");
   exit;
}

if (!isset($_SESSION['adm' ]) && !isset($_SESSION['user'])) {
   header("Location: ../index.php" );
    exit;
}

$offers = "";
$result = mysqli_query($connect, "SELECT * FROM offers");

while ($row = $result->fetch_array(MYSQLI_ASSOC)){
    $offers .=
    "<option value='{$row['id']}'>{$row['name']} </option>";
   }


?>
<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
    <meta name="viewport"  content="width=device-width, initial-scale=1.0">
   <?php require_once '../components/boot.php' ?>
   <title>hotelbooking  |  Add hotel</title >
   <style>
       fieldset {
           margin: auto;
           margin-top: 100px;
            width: 60% ;
       }      
   </style>
</head>
<body>
<fieldset>
  <legend class='h2' >Add Offers</legend>
  <form action="actions/createoffers.php"  
  method= "post" enctype= "multipart/form-data">
  <table  class='table'>
          <tr>
              <th>name</th>
              <td><input  class='form-control' type ="text" name="name"   placeholder="offername" /></td>
          </tr>   

          <tr>
              <th>content</th>
              <td><input  class='form-control' type ="text" name="content"   placeholder="What is this offer about?" /></td>
          </tr>  
          <tr>
              <th >Price</th>
              <td><input class='form-control'  type="number"  step="any" name = "price" placeholder= "Price" /></td>
          </tr>

          <tr>
              <th>hotel</th>
              <td><input  class='form-control' type ="text" name="hotel"   placeholder="hotelname" /></td>
          </tr>   

              <th >picture</th>
              <td ><input class= 'form-control' type= "file" name= "picture" /></td >
          </tr>
    
              <td ><button class = 'btn btn-success'   type = "submit" >Insert Offers </button ></td >
              <td ><a href = "index.php" ><button   class = 'btn btn-warning'   type = "button" >Home </button ></a ></td >
          </tr >
      </table >
  </form >
</fieldset >
</body >
</html >