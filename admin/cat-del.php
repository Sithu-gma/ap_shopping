<?php
    require 'config/db.php';
    require 'config/common.php';
    if($_GET['id']){
        $stmt=$pdo->prepare("DELETE FROM categories WHERE id=".$_GET['id']);
        $stmt->execute();
        header("location: cat-list.php?del=true");
    }

?>