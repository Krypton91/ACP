<?php
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$user = $_SESSION['user'];
include 'verifyPanel.php';
include 'header/header.php';
?>

    <!-- Content Wrapper. Contains page content -->
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
        <h1 style = "margin-top: 70px">Einstellungen</h1>

        <?php

        if (isset($_POST['updateButton'])) {
            $fail = false;

            if ($_POST['curPass'] != '') {
                $curPass = $_POST['curPass'];
                $curPass = hash('sha256', $curPass);
            } else {
                $fail = true;
            }

            if ($_POST['pass'] != '') {
                $pass = $_POST['pass'];
            } else {
                $fail = true;
            }

            if ($_POST['pass1'] != '') {
                $pass1 = $_POST['pass1'];
            } else {
                $fail = true;
            }
            if ($fail === false) {
                loginconnect();

                $SelectQ = "SELECT * FROM users WHERE username = '$user'";
                $result = mysqli_query($dbconL, $SelectQ);
                $dbPass = $result->fetch_object();
                $passR = $dbPass->password;

                if ($passR == $curPass) {
                    if ($pass == $pass1) {
                        //same

                echo '<div class="alert alert-success" role="alert"><a href="#" class="alert-link">Password changed.</a></div>';

                        $pass = hash('sha256', $pass);

                        $UpdateQ = "UPDATE users SET password='$pass' WHERE username='$user'";
                        mysqli_query($dbconL, $UpdateQ);
                    } else {
                        //not same

                  echo '<div class="alert alert-danger" role="alert"><a href="#" class="alert-link">Passwords do not match!</a></div>';
                    }
                } else {
                    echo'<div class="alert alert-danger" role="alert"><a href="#" class="alert-link">Current password is wrong!</a></div>';
                }
            } else {
                echo'<div class="alert alert-danger" role="alert"><a href="#" class="alert-link">Please fill both boxes!</a></div>';
            }
        }//end of update
        ?>

        <div class='panel panel-info'>
            <div class='panel-heading'>
            </div>
            <div class='panel-body'>
                <center><h4><?php echo $user; ?></h4>
                <br>
                <center><img alt="User Pic" src="images/man.png" class="img-circle img-responsive" width="150" height="150">
                <br>

                <form action = "profile.php" method="post">
                    <h4>Aktuelles Passwort</h4>
                    <input type="password" name= "curPass" class="form-control" value="" placeholder="Aktuelles Passwort">
                    <h4>Neues Passwort</h4>
                    <input type="password" name= "pass" class="form-control" value="" placeholder="Neues Passwort">
                    <h4>Passwort wiederholen</h4>
                    <input type="password" name= "pass1" class="form-control" value="" placeholder="Passwort wiederholen">
                    <br>
                    <button type="submit" name="updateButton" class="btn btn-primary btn-lg btn-block btn-outline">Speichern</button>
                </form>
            </div>
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="/dist/js/bootstrap.min.js"></script>
    <script src="../../assets/js/vendor/holder.min.js"></script>
    <script src="../../assets/js/ie10-viewport-bug-workaround.js"></script>
  </body>
</html>
