<?php
session_start();
ob_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

include 'verifyPanel.php';
masterconnect();

$page1 = $_GET['page'];
if ($page1 == '' || $page1 == '1') {
    $page = 0;
} else {
    $page = ($page1 * 50) - 50;
}

$resultQ = 'SELECT id FROM characters';
$result = mysqli_query($dbcon, $resultQ) or die('Connection could not be established');
$count = mysqli_num_rows($result);

$amount = $count / 50;
$amount = ceil($amount) + 1;
$currentpage = $page1;
$minusPage = $currentpage - 1;

if ($minusPage < 1) {
    $minusPage = 1;
}

$addPage = $currentpage + 1;
if ($addPage > $amount) {
    $addPage = $amount;
}
$max = PHP_INT_MAX;

switch ($_GET['search']) {
    case 'cash':
        $search = 'cash';
        break;
    case 'bank':
        $search = 'bank';
        break;
    case 'isWhitelisted':
        $search = 'isWhitelisted';
        break;
    case 'medic':
        $search = 'medic';
        break;
    case 'admin':
        $search = 'admin';
        break;
    default:
        $search = 'search';
        break;
}

if (isset($_POST['search'])) {
    $valuetosearch = $_POST['SearchValue'];
    $sqlget = "SELECT * FROM characters WHERE CONCAT (`ingameName`,`accountId`,`id`) LIKE '%".$valuetosearch."%'";
    $search_result = filterTable($dbcon, $sqlget);
    if ($search_result == '') {
        $sqlget = "SELECT * FROM characters WHERE CONCAT (`ingameName`,`accountId`,`id`) LIKE '%".$valuetosearch."%'";
        $search_result = filterTable($dbcon, $sqlget);
    }
} elseif (isset($_POST['orderTelefonnummer']) || $_GET['search'] == 'telefonnummer') {
    $sqlget = 'SELECT * FROM characters ORDER BY telefonnummer DESC limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
} elseif (isset($_POST['orderCash']) || $_GET['search'] == 'cash') {
    $sqlget = 'SELECT * FROM characters ORDER BY money DESC limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
} elseif (isset($_POST['orderisWhitelisted']) || $_GET['search'] == 'isWhitelisted') {
    $sqlget = 'SELECT * FROM characters ORDER BY isWhitelisted DESC limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
} elseif (isset($_POST['orderFührerschein']) || $_GET['search'] == 'führerschein') {
    $sqlget = 'SELECT * FROM characters ORDER BY drivingLicense DESC limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
} elseif (isset($_POST['orderAdmin']) || $_GET['search'] == 'admin') {
    $sqlget = 'SELECT * FROM characters ORDER BY adminlevel DESC limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
} else {
    $sqlget = 'SELECT * FROM characters limit '.$page.',50';
    $search_result = filterTable($dbcon, $sqlget);
}
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

    <form action = "players.php" method="post">
        <div class ="searchBar">
            <div class="row">
                <div class="col-lg-6">
                    <div class="input-group">
                        <input type="text" class="form-control" style = "width: 300px;" name="SearchValue" placeholder="Spielername/AccountID.....">
                        <span class="input-group-btn">
					<input class="btn btn-default" name="search" type="submit" value="Suchen">
				  </span>
                    </div><!-- /input-group -->
                </div><!-- /.col-lg-6 -->
            </div><!-- /.row -->
        </div>
    </form>

    <br>


    <div class="table-responsive">
        <table class="table table-striped" style = "margin-top: 0px">
            <thead>
            <tr>
                <th>Account ID</th>
                <th>Spieler Name</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            <?php

            while ($row = mysqli_fetch_array($search_result, MYSQLI_ASSOC)) {
                if ($row['accountId'] != '' || $row['id'] != '') {
                    if ($row['accountId'] == '') {
                        $pid = $row['id'];
                    } else {
                        $pid = $row['accountId'];
                    }
                }
                $temparrayIndex;
                echo '<td>'.$row['id'].'</td>';
                $temparrayIndex = $row['id'];
                echo '<td>'.utf8_encode($row['ingameName']).' </td>';
                echo '<td>'.$return.'</td>';
                echo '</td>';
                echo '<form action=editPlayer.php method=post>';
                echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit id=edit value=Edit".' ></td>';
                echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                echo "<td style='display:none;'>".'<input type=hidden name=guid value='.$return.'> </td>';
                echo '</form>';
                echo '</tr>';
            }
            echo '</table></div>';
            ?>

            <nav>
                <ul class="pagination">
                    <?php if ($currentpage != 1) {
                        ?>
                        <li>
                            <a href="players.php?search=<?php echo $search; ?>&page=<?php echo $minusPage; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>
                        <?php
                    } else {
                        ?>

                        <li class = "disabled">
                            <a href="players.php?search=<?php echo $search; ?>&page=<?php echo $minusPage; ?>" aria-label="Previous">
                                <span aria-hidden="true">&laquo;</span>
                            </a>
                        </li>

                        <?php
                    }
                    $amountPage = $currentpage + 2;
                    $pageBefore = $currentpage - 2;
                    if ($pageBefore == 0) {
                        $pageBefore = 1;
                        $amountPage = $amountPage + 1;
                    }
                    if ($pageBefore < 1) {
                        $pageBefore = 1;
                        $amountPage = $amountPage + 2;
                    }
                    for ($b = $pageBefore; $b <= $amountPage; ++$b) {
                    if ($b >= $amount) {
                        ?><li class = "disabled"><a href = "players.php?search=<?php echo $search; ?>&page=<?php echo $b; ?>" style = "text-decoration:none"><?php  echo $b.' '; ?></a><li><?php
                    } else {
                    if ($b == $currentpage) {
                        ?><li class = "active"><a href = "players.php?search=<?php echo $search; ?>&page=<?php echo $b; ?>" style = "text-decoration:none"><?php  echo $b.' '; ?></a><li><?php
                    } else {
                    ?><li><a href = "players.php?search=<?php echo $search; ?>&page=<?php echo $b; ?>" style = "text-decoration:none"><?php  echo $b.' '; ?></a><li><?php
                        }
                        }
                        }
                        if ($currentpage != $amount) {
                        ?>
                    <li>
                        <a href="players.php?search=<?php echo $search; ?>&page=<?php echo $addPage; ?>" aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                <?php
                } else {
                    ?>

                    <li class = "disabled">
                        <a href='players.php?search=<?php echo $search; ?>&page=<?php echo $minusPage; ?>' aria-label="Next">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>

                    <?php
                }
                ?>
                </ul>
            </nav>
            <script>
                function newAlert (type, message) {
                    $("#alert-area").append($("<div class='alert " + type + " fade in' data-alert><p> " + message + " </p></div>"));
                    $(".alert").delay(2000).fadeOut("slow", function () { $(this).remove(); });
                }
                function dbSave(value, uid, column, original){
                    if (value != original) {
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('Backend/updatePlayers.php',{column:column, editval:value, uid:uid},
                            function(){
                                location.reload();
                            });
                    };
                }
            </script>
            </tbody>
        </table>
    </div>
</div>
</div>
</div>
<script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
<script src="dist/js/bootstrap.min.js"></script>
</body>
</html>
