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
        if(empty($_POST['name'] ) || empty($_POST['desc']) || empty($_POST['price']) || empty($_POST['qty']) || empty($_POST['cat']) || empty($_FILES['img']['name'])) {
         
            if(empty($_POST['name'])) {
                $nameErr="Your name is empty.";
            }
            if(empty($_POST['desc'])) {
                $descErr="Your description is empty.";
            }
            if(empty($_POST['price'])) {
                $priceErr="Your price is empty.";
            }else if(is_numeric($_POST['price'])!= 1){
                $priceErr="Your price is not Number.";
            }
            if(empty($_POST['qty'])) {
                $qtyErr="Your qty is empty.";
            }else if(is_numeric($_POST['qty']) != 1){
                $qtyErr="Your qty is not Number.";
            }
            if(empty($_POST['cat'])) {
                $catErr="Your category_id is empty.";
            }
            if(empty($_FILES['img']['name'])) {
                $imgErr="Your img is empty.";
            }

        }else {      
            $file='img/'.($_FILES['img']['name']);
            // print_r($file);
            $ext=pathinfo($file, PATHINFO_EXTENSION);
            if($ext !="jpg" && $ext !="png" && $ext !="jpeg" && $ext !="jfif"){
                echo "<script>alert('File type is not match.');</script>";
            }else{
                $name=$_POST['name'];
                $desc=$_POST['desc'];
                $price=$_POST['price'];
                $qty=$_POST['qty'];
                $cat=$_POST['cat'];
                $img=$_FILES['img']['name'];
                move_uploaded_file($_FILES['img']['tmp_name'], $file);
                $stmt=$pdo->prepare("INSERT INTO products (name,description,price, qty, category_id,img , created_at) VALUES (:name,:description,:price ,
                :qty, :category_id,:img, now())");
                $result=$stmt->execute([
                    ':name'=>$name,
                    ':description'=>$desc,
                    ':price'=> $price,
                    ':qty'=> $qty,
                    ':category_id'=>$cat,
                    ':img'=>$img,
                ]);
               if($result) {
                echo "<script>alert('Successful Adding Catetory');window.location.href='index.php';</script>";
               }
            }          
           
        }
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <a href="index.php" class="btn btn-success">Category List</a>
        </div>
        <div class="card-body">
            <div class="card-title">
                <h1>Create New Product Form</h1>
            </div>
            <div class="card-text">
            <form action-add.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">

                <div class="mb-2">
                    <label for="">Product Name</label>
                    <p class="text-danger" ><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                    <input type="text" name="name" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descErr) ? '': $descErr ; ?></p>
                    <textarea class="form-control" name="desc"></textarea>
                </div>
              
                <div class="mb-2">
                    <label for="">Price</label>
                    <p class="text-danger"><?php echo empty($priceErr) ? '': $priceErr ; ?></p>
                    <input type="number" name="price" class="form-control">
                </div>
                <div class="mb-2">
                    <label for="">Quantity</label>
                    <p class="text-danger"><?php echo empty($qtyErr) ? '': $qtyErr ; ?></p>
                    <input type="number" name="qty" class="form-control">
                </div>
                <div class="mb-2">
                <?php 
                  $catStmt=$pdo->prepare("SELECT * FROM categories");  
                  $catStmt->execute();
                  $catResult=$catStmt->fetchAll();
                ?>
                    <label for="">Category</label>
                    <p class="text-danger"><?php echo empty($catErr) ? '': $catErr ; ?></p>
                   <select name="cat" id="" class="form-control">
                       <option value="0">Choose Categories</option>
                        <?php foreach($catResult as $data) : ?>
                            <option value="<?php echo escape($data['id'])?>">
                             <?php echo escape($data['name'])?>
                            </option>
                      <?php endforeach; ?>
                   </select>
                </div>
                <div class="mb-2">
                    <label for="">Image</label>
                    <p class="text-danger"><?php echo empty($imgErr) ? '': $imgErr ; ?></p>
                    <input type="file" name="img">
                </div> <br>
                <button type="submit" class="btn btn-primary">Add New Product</button>
            </form>
            </div>
            
        </div>

    </div>
</div>



<?php
require 'footer.php';
?>
