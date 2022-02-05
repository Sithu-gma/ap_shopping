<?php
session_start();
include 'config/db.php';
include 'config/common.php';

if(empty($_SESSION['user_id']) && empty($_SESSION['log_in'])){
  header('location: login.php');
}
require 'header.php';
?>
<div class="container-fluid mt-0" >
<div class="card">
    <div class="card-header">
      <a href="cat-add.php" class="btn btn-success">New Category</a>
    </div>
    <div class="card-body">
        <div class="card-title"></div>
        <table class="table">
            <?php
              if(!empty($_GET['pageno'])){
                $pageno=$_GET['pageno'];
              }else{
                $pageno=1;
              }
              $numRec=2;
              $offSet= ($pageno-1)*$numRec;

                $stmt=$pdo->prepare("SELECT * FROM categories");
                $stmt->execute();
                $rawResult=$stmt->fetchAll();
                $totalPages= ceil(count($rawResult)/$numRec);
                  if($totalPages){
                    $stmt=$pdo->prepare("SELECT * FROM categories ORDER BY id DESC LIMIT $offSet, $numRec");
                    $stmt->execute();
                    $result=$stmt->fetchAll();
                  }
            ?>
            <thead>
                <th>Name</th>
                <th>Description</th>
                <th>Action</th>
            </thead>
            <tbody>
               <?php foreach($result as $data): ?>
                <tr>
                   <td><?= $data['name']?></td>
                   <td><?= $data['description']?></td>
                   <td>
                          <div class="btn-group">
                            <div class="container">
                              <a href="cat-edit.php?id=<?= escape($data['id'])?>" class="btn btn-primary">Edit</a>
                            </div>
                            <div class="container">
                              <a href="cat-del.php?id=<?= escape($data['id']) ?>" class="btn btn-danger">DEL</a>
                            </div>
                          </div>
                        </td>
               </tr>
               <?php endforeach; ?>
            </tbody>
        </table>
        <nav aria-label="Page navigation example" style="float:right">
            <ul class="pagination">
              <li class="page-item"><a class="page-link" href="?$pageno=1">First</a></li>
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
