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

if ($staffPerms['superUser'] != '1') {
    header('Location: lvlError.php');
    die();
}

include 'header/header.php';
?>
<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
<h1 style = "margin-top: 70px">Settings Menu</h1>
    <p class="page-header">Settings menu of the panel, allows you to change panel settings.</p>
<div class='panel panel-info'>
    <div class='panel-heading'>
        <h3 class='panel-title'>Server Info</h3>
    </div>
<div class='panel-body'>

<form action = "updateSettings.php" method="post">
  <h4>Database Host</h4>
  <input type="text" name= "host" class="form-control" value="<?php echo $DBHost; ?>">

  <br>
  <h4>Username</h4>
  <input type="text" name= "user" class="form-control" value="<?php echo $DBUser; ?>">

  <br>
  <h4>Password</h4>
  <input type="password" name= "pass" class="form-control" value="<?php echo $DBPass; ?>">

  <br>
  <h4>Database Name</h4>
  <input type="text" name= "name" class="form-control" value="<?php echo $DBName; ?>">
  </select>


  <br>
  <button type="submit" name="updateButton" class="btn btn-primary btn-lg btn-block btn-outline">Update</button>
</form>

</div>
</div>
<?php


?>


          </div>
        </div>
      </div>
    </div>

    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <!-- Just to make our placeholder images work. Don't actually copy the next line! -->
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
