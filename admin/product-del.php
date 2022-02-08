<?php
    require 'config/db.php';
    require 'config/common.php';
    if($_GET['id']){
        $stmt=$pdo->prepare("DELETE FROM products WHERE id=".$_GET['id']);
        $stmt->execute();
        header("location:index.php?del=true");
    }

?>