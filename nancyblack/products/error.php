<!DOCTYPE html>
<html lang="en" >
   <head>
       <meta charset="UTF-8">
        <title>Error</title>
        <?php require_once 'components/boot.php'?>   
    </head>
   <body>
       <div  class="container"> 
           <div class="mt-3 mb-3" >
               <h1>Invalid Request</h1>
           </div>
            <div class="alert alert-warning" role="alert">
               <p>You've made an invalid request. Please <a href="index.php" class ="alert-link">go back</a> to index and try again.</p>
           </div>
        </div>
   </body>
</html>
<!-- in line 6 there was a failure in prework! it said actions/boot.php but actually the file
// was in components/boot so we changed it ! now its fine//  