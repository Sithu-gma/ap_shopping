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
        if(empty($_POST['name'] ) || empty($_POST['desc']) || empty($_POST['price']) || empty($_POST['qty']) || empty($_POST['cat']) || empty($_FILES['img'])) {
         
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
            if(empty($_FILES['img'])) {
                $imgErr="Your img is empty.";
            }

        }else {      
            if($_FILES['img']['name']!=null) {
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
                    $stmt=$pdo->prepare("UPDATE products Set name=:name, description=:desc, price=:price, qty=:qty, category_id=:category_id,
                    img=:img WHERE id=:id");
                    $result=$stmt->execute([
                        ':id'=>$id,
                        ':name'=>$name,
                        ':desc'=>$desc,
                        ':price'=> $price,
                        ':qty'=> $qty,
                        ':category_id'=>$cat,
                        ':img'=>$img,
                    ]);
                   if($result) {
                    echo "<script>alert('Successful Adding Catetory');window.location.href='index.php';</script>";
                   }
                }  
            }else{
                $id=$_POST['id'];
                $name=$_POST['name'];
                $desc=$_POST['desc'];
                $price=$_POST['price'];
                $qty=$_POST['qty'];
                $cat=$_POST['cat'];
                $stmt=$pdo->prepare("UPDATE products Set name=:name, description=:desc, price=:price, qty=:qty, category_id=:category_id WHERE id=:id");
                $result=$stmt->execute([
                    ':name'=>$name,
                    ':desc'=>$desc,
                    ':price'=> $price,
                    ':qty'=> $qty,
                    ':category_id'=>$cat,
                    ':id'=>$id,
                ]);
               if($result) {
                echo "<script>alert('Successful Adding Catetory');window.location.href='index.php';</script>";
               }
            
            }
           
        }        
    }
   
    $stmt=$pdo->prepare("SELECT * FROM products WHERE id=".$_GET['id']);  
    $stmt->execute();
    $result=$stmt->fetchAll();
  
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <a href="index.php" class="btn btn-success">Product List</a>
        </div>
        <div class="card-body">
            <div class="card-title">
                <h1>Edit Product Form</h1>
            </div>
            <div class="card-text">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                <input type="hidden" name="id" value="<?= escape($result[0]['id']);?>" >

                <div class="mb-2">
                    <label for="">Product Name</label>
                    <p class="text-danger" ><?php echo empty($nameErr) ? '' : $nameErr; ?></p>            
                    <input type="text" name="name" class="form-control" value="<?= escape($result[0]['name'])?>">
                </div>
                <div class="mb-2">
                    <label for="">Description</label>
                    <p class="text-danger"><?php echo empty($descErr) ? '': $descErr ; ?></p>
                    <textarea class="form-control" name="desc"><?= escape($result[0]['description'])?></textarea>
                </div>
              
                <div class="mb-2">
                    <label for="">Price</label>
                    <p class="text-danger"><?php echo empty($priceErr) ? '': $priceErr ; ?></p>
                    <input type="number" name="price" class="form-control" value="<?= escape($result[0]['price'])?>">
                </div>
                <div class="mb-2">
                    <label for="">Quantity</label>
                    <p class="text-danger"><?php echo empty($qtyErr) ? '': $qtyErr ; ?></p>
                    <input type="number" name="qty" class="form-control" value="<?= escape($result[0]['qty'])?>">
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
                            <?php if($data['id']==$result[0]['category_id']): ?>
                                <option value="<?= escape($data['id'])?>" selected>
                                    <?= escape($data['name'])?>
                                </option>
                            <?php else : ?>
                                <option value="<?= escape($data['id'])?>" >
                                    <?= escape($data['name'])?>
                                </option>
                            <?php endif; ?>
                        <?php endforeach; ?>
                   </select> 
                </div>
                <div class="mb-2">
                    <label for="">Image</label>
                    <p class="text-danger"><?php echo empty($imgErr) ? '': $imgErr ; ?></p>
                    <img src="img/<?=escape($result[0]['img'])?>" alt="" width="150px" height="150px">
                    <br>
                    <input type="file" name="img" value="<?= escape($result[0]['img'])?>">
                    
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
