
<?php
   session_start();

   if(isset($_SESSION["newsession"])){
     include 'dashboard.php';
   }else{
     include 'login.php';
   }


?>
