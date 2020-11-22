<?php
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

if ($staffPerms['superUser'] != '1') {
    header('Location: lvlError.php');
    die();
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

		  <div class="btn-group" role="group" aria-label="...">
			<FORM METHOD="LINK" ACTION="addStaff.php">
			<INPUT class='btn btn-primary btn-outline' TYPE="submit" VALUE="New Staff User">
			</FORM>
		  </div>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Reset Entire Panel</h4>
      </div>
      <div class="modal-body">
        Are you sure you want to reset the entire panel?
      </div>
      <div class="modal-footer">
        <FORM METHOD="LINK" ACTION="delete.php">
        <button type="submit" class="btn btn-danger">Reset</button>

        <button type="button" class="btn btn-primary btn-outline" data-dismiss="modal">Close</button>
        </form>
      </div>
    </div>
  </div>
</div>

			<br><br>
<?php
if ($staffPerms['superUser'] != '1') {
    header('Location: lvlError.php');
}

include 'verifyPanel.php';
loginconnect();

$sqlget = 'SELECT * FROM users';
$sqldata = mysqli_query($dbconL, $sqlget) or die('Connection could not be established');

if (isset($_POST['delete'])) {
    $sql = "DELETE FROM users WHERE ID='$_POST[hidden]'";
    mysqli_query($dbconL, $sql);

    echo '<div class="alert alert-success" role="alert"><a href="#" class="alert-link">Staff account deleted!</a></div>';
}

if (isset($_POST['update'])) {
    if ($_POST['password'] == '') {
        $UpdateQ = "UPDATE users SET username='$_POST[username]' WHERE ID='$_POST[hidden]'";
        mysqli_query($dbconL, $UpdateQ);
        echo '<div class="alert alert-success" role="alert"><a href="#" class="alert-link">Username updated!</a></div>';
    } else {
        $password = $_POST['password'];
        $pass = hash('sha256', $password);
        $UpdateQ = "UPDATE users SET username='$_POST[username]', password='$pass' WHERE ID='$_POST[hidden]'";
        mysqli_query($dbconL, $UpdateQ);
        echo '<div class="alert alert-success" role="alert"><a href="#" class="alert-link">Password and/or username updated!</a></div>';
    }
}

?>
          <div class="table-responsive">
            <table class="table table-striped" style = "margin-top: -10px">
              <thead>
                <tr>
					<th>Username</th>
					<th>Password</th>
					<th>Delete</th>
					<th>Update</th>
					<th>Permissions</th>
                </tr>
              </thead>
              <tbody>
<?php
while ($row = mysqli_fetch_array($sqldata, MYSQLI_ASSOC)) {
    echo '<form action=staff.php method=post>';
    echo '<tr>';
    echo '<td>'."<input class='form-control' type=text name=username value=".$row['username'].' </td>';
    echo '<td>'."<input class='form-control' type=text name=password placeholder='New password' </td>";
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=delete value=Delete".' </td>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=update value=Update".' </td>';
    echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['ID'].' </td>';
    echo '</form>';
    echo '<form action=permissions.php method=post>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit value=Edit".' </td>';
    echo "<td style='display:none;'>".'<input type=hidden name=hiddenId value='.$row['ID'].' </td>';
    echo '</form>';
    echo '</tr>';
}

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
    <script src="dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
