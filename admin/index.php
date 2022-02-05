<?php 
  session_start();
  require "config/db.php";
  require "config/common.php";
  if(empty($_SESSION['user_id']) and empty($_SESSION['logged_in'])) {
    header("location:login.php");
  }
 
  include('header.php');
?>
  
                     
           
  <?php  include('footer.php'); ?>
