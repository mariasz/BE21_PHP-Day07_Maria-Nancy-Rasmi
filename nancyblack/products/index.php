<?php 
require_once '../components/db_connect.php';
if (isset($_SESSION['user']) != "") {
    header("Location: ../home.php");
    exit;
 }
 
 if (!isset($_SESSION['adm']) && !isset($_SESSION['user'])) {
    header("Location: ../index.php" );
     exit;
 } 

$sql = "SELECT * FROM offers";
$result = mysqli_query($connect ,$sql);
$tbody=''; //this variable will hold the body for the table
if(mysqli_num_rows($result)  > 0) {    
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){        
       $tbody .= "<tr> 
            <td><img class='img-thumbnail' src='pictures/" .$row['picture']."'</td>
           <td>" .$row['name']."</td>
            <td>" .$row['content']."</td>
            <td>" .$row['price']."</td>
            <td>" .$row['date']."</td>
           <td><a href='update.php?id=" .$row['id']."'><button class='btn btn-primary btn-sm' type='button'>Edit</button></a>
           <a href='delete.php?id=" .$row['id']."'><button class='btn btn-danger btn-sm' type='button'>Delete</button></a></td>
           </tr>";
   };
} else {
   $tbody =  "<tr><td colspan='5'><center>No Data Available </center></td></tr>";
}

$connect->close(); //this is object oriented its not major - 
//you could also do it like: mysqli_close($connect); (would be procedural way)
?>

<!DOCTYPE html>
<html lang="en" >
   <head>
       <meta charset="UTF-8">
       <meta name="viewport"  content="width=device-width, initial-scale=1.0">
       <title>hotelbooking</title>
       <?php require_once '../components/boot.php' ?>
       <style type= "text/css">
           .manageProduct {          
               margin: auto;
           }
           .img-thumbnail {
               width: 70px !important;
                height: 70px !important;
           }
           td {          
               text-align: left;
               vertical-align: middle;

            }
           tr {
               text-align: center;
           }
       </style>
   </head>
   <body>
       <div class="manageProduct w-75 mt-3" >   
           <div class='mb-3'>

                <a href= "create.php" ><button class='btn btn-primary'type = "button" >Add offers</button></a>
            </div>
           <p  class='h2'>offers</p>

            <table class='table table-striped'>
               <thead class='table-success' >
                   <tr>

                        <th>Picture</th>
                       <th>Name</th>
                       <th>Content</th>
                       <th>Price</th>
                       <th>Date</th>
                        <th>Action</th>
                   </tr>
               </thead>
               <tbody>
                    <?= $tbody;?> 

                </tbody>
            </table>
       </div>
    </body>
</html>

<!-- you see line 68 thats a shortcut for echo! without this nothing will be shown on screen! --> 
