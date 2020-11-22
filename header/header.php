<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>La-La-Land | ACP - Dashboard</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Tempusdominus Bbootstrap 4 -->
  <link rel="stylesheet" href="plugins/tempusdominus-bootstrap-4/css/tempusdominus-bootstrap-4.min.css">
  <!-- iCheck -->
  <link rel="stylesheet" href="plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- JQVMap -->
  <link rel="stylesheet" href="plugins/jqvmap/jqvmap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="dist/css/adminlte.min.css">
  <!-- overlayScrollbars -->
  <link rel="stylesheet" href="plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  <!-- Daterange picker -->
  <link rel="stylesheet" href="plugins/daterangepicker/daterangepicker.css">
  <!-- summernote -->
  <link rel="stylesheet" href="plugins/summernote/summernote-bs4.css">
  <!-- Google Font: Source Sans Pro -->
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
    <!-- Modul import sehr whichtig fürs updaten! -->
    <script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>
    <!-- normal script imports etc  -->
    <script src="scripts/jquery-1.12.3.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="scripts/jquery.backstretch.js"></script>
    <!-- Insert this line after script imports -->
    <script>if (window.module) module = window.module;</script>
</head>

<body>

<!-- Main Sidebar Container -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="https://la-la-land.eu" class="brand-link">
      <img src="dist/img/LALA_HEADERLOGO.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">La-La-Land [ACP]</span>
    </a>
    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="profile.php" class="d-block"><?php echo $_SESSION['user']; ?></a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <li class="nav-item">
            <a href="home.php" class="nav-link">
              <i class="fas fa-home"></i>
              <?php
                echo '<p>Dasboard</p>';
                ?>
            </a>
            <li class="nav-item">
            <a href="players.php" class="nav-link">
            <i class="fas fa-gamepad"></i>
              <?php
                echo '<p>Spieler</p>';
                ?>
            </a>
          </li>
          <?php
                $staffPerms = $_SESSION['perms'];
                switch ($staffPerms) 
                {
                    case $staffPerms['whitelist'] == '1':
                      ?>
                      <li class="nav-item">
                        <a href="SocialClub.php" class="nav-link">
                        <i class="fas fa-user-plus"></i>
                        <?php
                          echo '<p>Social Club</p>';
                        ?>
                        </a>
                        </li>
                      <?php
                        case $staffPerms['logs'] == '1':
                      ?>
                      <li class="nav-item">
                        <a href="IngameLogs.php" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <?php
                          echo '<p>Logs</p>';
                        ?>
                        </a>
                        </li>
                        <?php
                    case $staffPerms['superUser'] == '1':
                      ?>
                      <li class="nav-item">
                        <a href="logs.php" class="nav-link">
                        <i class="fas fa-book-open"></i>
                        <?php
                          echo '<p>ACP-Logs</p>';
                        ?>
                        </a>
                        </li>
                      <?php
                    case $staffPerms['superUser'] == '1':
                      ?>
                      <li class="nav-item">
                        <a href="staff.php" class="nav-link">
                        <i class="fas fa-user"></i>
                        <?php
                          echo '<p>Accounts</p>';
                        ?>
                        </a>
                        </li>
                      <?php
                }
                ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
    
  </aside>