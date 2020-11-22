<?php
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$perms = '"[[`money`,1],[`telnumber`,1],[`whitelist`,1],[`driverlicense`,1],[`ACP_LOGS`,0],[`bank`,1],[`superUser`,0],[`pwchange`,1],[`hwid`,1],[`logs`,0],[`ban`,1]]"';

if ($staffPerms['superUser'] != '1') {
    header('Location: lvlError.php');
    die();
}

include 'verifyPanel.php';
loginconnect();
?>

<?php

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

		  	<div class="btn-group" role="group" aria-label="...">
			<FORM METHOD="LINK" ACTION="staff.php">
			<INPUT class='btn btn-primary btn-outline' TYPE="submit" VALUE="Back">
			</FORM>
			</div><br><br><br>

<?php
if (isset($_POST['update'])) {
    if ($staffPerms['superUser'] == '1') {
        $username = mysqli_real_escape_string($dbconL, $_POST['username']);
        $password = mysqli_real_escape_string($dbconL, $_POST['password']);

        $encPass = hash('sha256', $password);

        $UpdateQ = "INSERT INTO users (username, password, permissions) VALUES ('$username', '$encPass', '$perms')";
        mysqli_query($dbconL, $UpdateQ);

        echo '<div class="alert alert-success" role="alert"><a href="#" class="alert-link">User successfully added!</a></div>';
    } else {
        echo '<div class="alert alert-danger" role="alert"><a href="#" class="alert-link">Nope...</a></div>';
    }
}
?>

          <div class="table-responsive">
            <table class="table table-striped" style = "margin-top: -10px">
              <thead>
                <tr>
					<th>Benutzername</th>
					<th>Kennwort</th>
					<th></th>
                </tr>
              </thead>
              <tbody>
<?php
echo '<form action=addStaff.php method=post>';
  echo '<tr>';

  echo '<td>'."<input class='form-control' type=text name=username value='' </td>";
  echo '<td>'."<input class='form-control' type=text name=password value=''</td>";

  echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=update value=Add".' </td>';

  echo '</tr>';
  echo '</form>';

echo '</table></div>';
?>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
  </body>
</html>
