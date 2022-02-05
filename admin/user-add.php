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
        if(empty($_POST['name'] ) && empty($_POST['phone']) && empty($_POST['email']) && empty($_POST['address']) && empty($_POST['password'])) {
            if(empty($_POST['name'])) {
                $nameErr="Your name is empty.";
            }
            if(empty($_POST['phone'])) {
                $phErr="Your Ph No. is empty.";
            }
            if(empty($_POST['email'])) {
                $emailErr="Your email is empty.";
            }
            if(empty($_POST['address'])) {
                $addErr="Your  Address is empty.";
            }
            if(empty($_POST['password'])) {
                $passErr="Your password is empty.";
            }

        }else {          
          
            $name=$_POST['name'];
            $email=$_POST['email'];
            $phone=$_POST['phone'];
            $password=password_hash($_POST['password'],PASSWORD_DEFAULT);
            $address=$_POST['address'];
            if(empty($_POST['role'])){
                $role=0;
            }else {
                $role=1;
            }

            $stmt=$pdo->prepare("SELECT * FROM users WHERE email=:email");
            $stmt->bindValue(':email',$email);
            $stmt->execute();
            $user=$stmt->fetch(PDO::FETCH_ASSOC);
            if($user) {
                echo "<script> alert('EMAIL DUPLICATED');</script>";
            }else{
                $stmt=$pdo->prepare("INSERT INTO users (name,email, phone, password, address,role, created_at) VALUES (:name,:email,:phone,:password,:address,:role, now())");
                $result=$stmt->execute([
                    ':name'=>$name,
                    ':email'=>$email,
                    ':phone'=>$phone,
                    ':password'=>$password,
                    ':address'=>$address,
                    ':role'=>$role,
                    
                ]);
               if($result) {
                echo "<script>alert('Successful Adding New USERS');window.location.href='user-list.php';</script>";
               }
            }           
        }
    }
?>
<div class="container-fluid">
    <div class="card">
        <div class="card-header">
        <a href="user-list.php" class="btn btn-success">User List</a>
        </div>
        <div class="card-body">
            <div class="card-title">
                <h1>Create New USER</h1>
            </div>
            <div class="card-text">
            <form action="user-add.php" method="post">
                <input type="hidden" name="_token" value="<?php echo $_SESSION['_token']; ?>">
                <div class="form-group">
                    <label for="">NAME</label>
                    <p class="text-danger" ><?php echo empty($nameErr) ? '' : $nameErr; ?></p>
                    <input type="text" name="name" class="form-control">
                </div>
               
                <div class="form-group">
                    <label for="">PHONE</label>
                    <p class="text-danger" ><?php echo empty($phErr) ? '' : $phErr; ?></p>
                    <input type="text" name="phone" class="form-control">
                </div>
                <div class="form-group">
                    <label for="">EMAIL</label>
                    <p class="text-danger"><?php echo empty($emailErr) ? '': $emailErr ; ?></p>
                    <input type="email" class="form-control" name="email">
                </div>
                <div class="form-group">
                    <label for="">PASSWORD</label>
                    <p class="text-danger"><?php echo empty($passErr) ? '': $passErr ; ?></p>
                    <input type="password" class="form-control" name="password">
                </div>
                <div class="form-group">
                    <label for="">ADDRESS</label>
                    <p class="text-danger"><?php echo empty($addErr) ? '': $addErr ; ?></p>
                    <input type="text" class="form-control" name="address">
                </div>
                <div class="form-group">
                    <label for="vehicle3"> Admin</label><br>
                    <input type="checkbox" name="role" value="1">
                  </div>
                <br>
                <button type="submit" class="btn btn-primary">Add User</button>
            </form>
            </div>
            
        </div>

    </div>
</div>



<?php
require 'footer.php';
?>
