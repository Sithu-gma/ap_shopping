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
          
            $name=$_POST['name'];
            $desc=$_POST['desc'];
            $stmt=$pdo->prepare("INSERT INTO categories (name,description, created_at) VALUES (:name,:description,now())");
            $result=$stmt->execute([
                ':name'=>$name,
                ':description'=>$desc,
            ]);
           if($result) {
            echo "<script>alert('Successful Adding Catetory');window.location.href='cat-list.php';</script>";
           }
        }
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <a href="cat-list.php" class="btn btn-success">Category List</a>
        </div>
        <div class="card-body">
            <div class="card-title">
                <h1>Create New Category Form</h1>
            </div>
            <div class="card-text">
            <form action="cat-add.php" method="post">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

                <div class="mb-2">
                    <label for="">Category Name</label>
                    <p class="text-danger" ><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descErr) ? '': $descErr ; ?></p>
                    <textarea class="form-control" name="desc"></textarea>
                </div><br>
                <button type="submit" class="btn btn-primary">Add Category</button>
            </form>
            </div>
            
        </div>

    </div>
</div>



<?php
require 'footer.php';
?>
