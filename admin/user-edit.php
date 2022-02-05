<?php
session_start();
include 'config/db.php';
include 'config/common.php';
if(empty($_SESSION['user_id']) && empty($_SESSION['log_in'])){
    header('location: login.php');
  }
require 'header.php';
?>
<?php
    if($_POST){        
        if(empty($_POST['name'] )&& empty($_POST['desc'])) {
            if(empty($_POST['name'])) {
                $nameErr="Your name is empty.";
            }
            if(empty($_POST['desc'])) {
                $descErr="Your description is empty.";
            }

        }else {          
            $id=$_POST['id'];
            $name=$_POST['name'];
            $desc=$_POST['desc'];
            

            $stmt=$pdo->prepare("UPDATE categories SET name=:name, description=:description WHERE id=:id");
            $result=$stmt->execute([
                ':id'=>$id,
                ':name'=>$name,
                ':description'=>$desc,
            ]);
           if($result) {
            echo "<script>alert('Successful Updating Catetory');window.location.href='cat-list.php';</script>";
           }
        }
    }
    $stmt=$pdo->prepare("SELECT * FROM categories WHERE id=".$_GET['id']);
    $stmt->execute();
    $result=$stmt->fetchAll();
    // print("<pre>");
    // print_r($result);
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <a href="cat-list.php" class="btn btn-success">Category List</a>
        </div>
        <div class="card-body">
            <div class="card-title">
                <h1>Edit Category Form</h1>
            </div>
            <div class="card-text">
            <form action="" method="post">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?=$result[0]['id']?>">
                <div class="mb-2">
                    <label for="">Category Name</label>
                    <p class="text-danger" ><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                    <input type="text" name="name" class="form-conrtol" value="<?=$result[0]['name']?>">
                </div>
                <div class="mb-2">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descErr) ? '': $descErr ; ?></p>
                    <textarea class="form-conrtol" name="desc" ><?= $result[0]['description']?></textarea>
                </div>
                <br>
                <button type="submit" class="btn btn-primary"> Category</button>
            </form>
            </div>
            
        </div>

    </div>
</div>



<?php
require 'footer.php';
?>
