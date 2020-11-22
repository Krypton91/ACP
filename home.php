<?php
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

$conecG = 'work';
$_SESSION['conecFail'] = $conecG;

include 'verifyPanel.php';
masterconnect();

$players = 0;
$money = 0;

$sqlget = 'SELECT * FROM accounts';
$sqldata = mysqli_query($dbcon, $sqlget) or die('Connection could not be established by owner ID');

while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    ++$players;
    $money = $money + $row['amount'];
}

$sqlgetVeh = 'SELECT * FROM vehicles';
$sqldataVeh = mysqli_query($dbcon, $sqlgetVeh) or die('Connection could not be established');
$vehicles = mysqli_num_rows($sqldataVeh);

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
<?php
    echo '<!-- Content Wrapper. Contains page content -->';
    echo '<div class="content-wrapper">';
    echo '<!-- Content Header (Page header) -->';
    echo '<div class="content-header">';
    echo '<div class="container-fluid">';
    echo '<div class="row mb-2">';
    echo '<div class="col-sm-6">';
    echo '<h1 class="m-0 text-dark">Home-Menu</h1>';
    echo '</div><!-- /.row -->';
    echo '</div><!-- /.container-fluid -->';
    echo '</div>';
    echo '<!-- Main content -->';
    echo '<section class="content">';
    echo '<div class="container-fluid">';
    echo '<!-- Small boxes (Stat box) -->';
    echo '<div class="row">';
    echo '<div class="col-lg-3 col-6">';
    echo '<!-- small box -->';
    echo '<div class="small-box bg-warning">';
    echo '<div class="inner">';
    echo '<h3>'.$players.'</h3>';
    echo '';
    echo '<p>Whitelisted Spieler</p>';
    echo '</div>';
    echo '<div class="icon">';
    echo '<i class="ion ion-person-add"></i>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<!-- ./col -->';
    echo '<div class="col-lg-3 col-6">';
    echo '<!-- small box -->';
    echo '<div class="small-box bg-success">';
    echo '<div class="inner">';
    echo '<h3>'.$vehicles.'</h3>';
    echo '<p>Fahrzeuge auf dem Server.</p>';
    echo '</div>';
    echo '<div class="icon">';
    echo '<i class="fas fa-truck-moving"></i>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
    echo '<!-- ./col -->';
    echo '<div class="col-lg-3 col-6">';
    echo '</div>';
    echo '<!-- ./col -->';
    echo '<div class="col-lg-3 col-6">';
    echo '</div>';
    echo '<!-- ./col -->';
    echo '</div>';
ob_end_flush();
?>
<div class="card">
              <div class="card-header border-transparent">
                <h3 class="card-title">Online Spieler:</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body p-0">
                <div class="table-responsive">
                  <table class="table m-0">
                    <thead>
                    <tr>
                      <th>Account ID</th>
                      <th>Spieler Name</th>
                      <th>Edit</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                  $sqlget1 = 'SELECT * FROM characters';
                  $search_result1 = filterTable($dbcon, $sqlget1);
                  while ($row = mysqli_fetch_array($search_result1, MYSQLI_ASSOC)) {
                  //Ignoriert alle einträge wo isOnline auf N steht! :)
                  if($row['isOnline'] != 'Y')
                    continue;
                    if ($row['accountId'] != '' || $row['id'] != '') {
                    if ($row['accountId'] == '') {
                        $pid = $row['id'];
                      } else {
                        $pid = $row['accountId'];
                      }
                    }
                    echo '<tr>';
                    echo '<td>'.$row['id'].'</td>';
                    echo '<td>'.utf8_encode($row['ingameName']).' </td>';
                    echo '</td>';
                    echo '<form action=editPlayer.php method=post>';
                    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit id=edit value=Edit".' ></td>';
                    echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                    echo "<td style='display:none;'>".'<input type=hidden name=guid value='.$return.'> </td>';
                    echo '</a>';
                    echo '</tr>';
                  }

                  /*
                  echo '<form action=editPlayer.php method=post>';
                echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit id=edit value=Edit".' ></td>';
                echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                echo "<td style='display:none;'>".'<input type=hidden name=guid value='.$return.'> </td>';
                echo '</form>';
                */
                    ?>
                    </tbody>
                  </table>
                </div>
                <!-- /.table-responsive -->
              </div>
              <!-- /.card-footer -->
            </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>