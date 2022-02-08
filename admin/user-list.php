<?php
session_start();
include 'config/db.php';
include 'config/common.php';
  if(empty($_SESSION['user_id']) && empty($_SESSION['log_in'])){
    header('location: login.php');
  } 

  if (isset($_POST['search'])) {
    setcookie("search",$_POST['search'], time() + (86400 * 30), "/");
  }else{
    if (empty($_GET['pageno'])) {
      unset($_COOKIE['search']); 
      setcookie("search", null, -1, '/'); 
    }
  }
  
  
?>
<?php require 'header.php';?>
<div class="container-fluid mt-0" >
<div class="card">
    <div class="card-header">
      <a href="user-add.php" class="btn btn-success">New User</a>
    </div>
    <?php if(isset($_GET['del'])): ?>
      <div class="alert alert-warning">
        A role deleted!
      </div>
    <?php endif ?>
    <div class="card-body">
        <div class="card-title"></div>
        <table class="table">
            <!-- pagination/ db -->           
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $numRec=2;
              $offSet=($pageno-1) *$numRec;
              
                if(empty($_POST['search']) && empty($_COOKIE['search'])){
                  $stmt=$pdo->prepare("SELECT * FROM users ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult=$stmt->fetchAll();
                  $totalPages=ceil(count($rawResult)/$numRec);
                  
                    $stmt=$pdo->prepare("SELECT * FROM users ORDER BY id DESC LIMIT $offSet, $numRec");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                  
                }else{
                  $searchKey = !empty($_POST['search']) ? $_POST['search'] : $_COOKIE['search'];                  
                  $stmt=$pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC");
                  $stmt->execute();
                  $rawResult=$stmt->fetchAll();
                  $totalPages=ceil(count($rawResult)/$numRec);
                  
                    $stmt=$pdo->prepare("SELECT * FROM users WHERE name LIKE '%$searchKey%' ORDER BY id DESC LIMIT $offSet, $numRec");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                  
                }

            ?>
            <thead>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </thead>
            <tbody>
               <?php foreach($result as $data): ?>
                <tr>
                   <td><?= $data['name']?></td>
                   <td><?= $data['email']?></td>
                   <td><?= $data['role']==1 ? "ADMIN": "USER"; ?></td>
                   <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="user-edit.php?id=<?= escape($data['id'])?>" class="btn btn-primary">Edit</a>
                            </div>
                            <div class="container">
                              <a href="user-del.php?id=<?= escape($data['id']) ?>" class="btn btn-danger">DEL</a>
                            </div>
                          </div>
                        </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
        </table>
        <!-- pagination  -->
          <nav aria-label="Page navigation example" style="float:right">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?pageno=1">First</a></li>
              <li class="page-item <?php if($pageno <= 1){echo 'disabled';} ?>">
                <a class="page-link" href="<?php if($pageno<=1) { echo '#';} else{ echo '?pageno='.($pageno-1);}?>">Previous</a>
              </li>
              <li class="page-item"><a class="page-link" href="#">
                <?= $pageno ?></a>
              </li>
              <li class="page-item <?php if($pageno>=$totalPages){echo 'disabled';} ?>">
                <a class="page-link" href="<?php if($pageno >= $totalPages) {echo '#';} else{echo '?pageno='.($pageno+1);}?>">Next</a>
              </li>
              <li class="page-item">
                <a class="page-link" href="?pageno=<?= $totalPages ?>">Last</a>
              </li>
            </ul>
          </nav>
        
    </div>

</div>
</div>



<?php
require 'footer.php';
?>
