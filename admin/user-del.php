<?php
    require 'config/db.php';
    require 'config/common.php';
    if($_GET['id']){
        $stmt=$pdo->prepare("DELETE FROM users WHERE id=".$_GET['id']);
        $stmt->execute();
        header("location: user-list.php?del=true");
    }

?>