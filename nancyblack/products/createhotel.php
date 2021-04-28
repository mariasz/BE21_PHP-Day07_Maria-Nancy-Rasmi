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

$hotel = "";
$result = mysqli_query($connect, "SELECT * FROM hotel");

while ($row = $result->fetch_array(MYSQLI_ASSOC)){
      $hotel .=
"<option value='{$row['hotel_id']}'>{$row['hotelname']}</option>";
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
  <legend class='h2' >Add Hotel</legend>
  <form action="actions/a_create.php"  
  method= "post" enctype= "multipart/form-data">
  <table  class='table'>
          <tr>
              <th>hotelname</th>
              <td><input  class='form-control' type ="text" name="name"   placeholder="hotelname" /></td>
          </tr>   

          <tr>
              <th>hotellocation</th>
              <td><input  class='form-control' type ="text" name="content"   placeholder="Where is this hotel?" /></td>
          </tr>  
          <tr>
              <th >picture</th>
              <td ><input class= 'form-control' type= "file" name= "picture" /></td >
          </tr>
    
              <td ><button class = 'btn btn-success'   type = "submit" >Insert Hotel </button ></td >
              <td ><a href = "index.php" ><button   class = 'btn btn-warning'   type = "button" >Home </button ></a ></td >
          </tr >
      </table >
  </form >
</fieldset >
</body >
</html >