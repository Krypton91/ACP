<?php
error_reporting(0);
session_start();
ob_start();
$version = '';

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

include 'verifyPanel.php';
masterconnect();

$guidPlayer = htmlspecialchars($_POST['guid']);
$uidPlayer = mysqli_real_escape_string($dbcon, $_POST['hidden']);

include 'header/header.php';
?>

<!-- Content Wrapper. Contains page content -->
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav ml-auto">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="logout.php" role="button"><i class="fas fa-bars"></i> Logout</a>
      </li>
    </ul>
</nav>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">
    <br>


    <div class="table-responsive">
        <table class="table table-striped" style = "margin-top: 0px">
            <thead>
            <tr>
                <th>ItemName</th>
                <th>Anzahl</th>
                <th>Action:</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php
                    $sqlGetQueryInv = "SELECT * FROM `vehicle_items` WHERE vehId='$uidPlayer'";
                    $search_result22 = mysqli_query($dbcon, $sqlGetQueryInv) or die('Connection could not be established');
                    
                    while ($row = mysqli_fetch_array($search_result22, MYSQLI_ASSOC)) {
                        $ItemID = $row['itemId'];
                        $sqlGetQueryInv = "SELECT * FROM `items` WHERE id='$ItemID'";

                        $result5 = mysqli_query($dbcon, $sqlGetQueryInv);
                        $Item = $result5->fetch_object();
                        $ItemName = $Item->itemName;
                         ?>
                        <?php
                          echo '<td>'.$ItemName.'</td>';
                          echo '<td>'.$row['amount'].' </td>';
                          echo '</td>';
                          echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                          echo '<td><button type="button" class="btn btn-default" value='.$row['id'].' onclick="dbSave(this.value);"><i class="fas fa-trash-alt"></i></button></td>';
                          echo '</form>';
                          echo '</tr>';
                    }
                ?>
            </tbody>
        </table>
    </div>
    <script>
                function dbSave(id){
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('DeleteVehicleInvItem.php',{id:id},
                            function(){
                                location.reload();
                            });
                }
            </script>
</div>
</div>
</div>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>
