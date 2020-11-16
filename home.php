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
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 style = "margin-top: 70px">Dashboard</h1>
<?php
    //Max players
    echo '
    <div class="row">
    <div class="col-md-4">
    ';

    echo    "<div id='rcorners1'>";
    echo        "<div class='box-top'><center><h1>Spieler</h1></div>";
    echo        "<div class='box-panel'><p></p>";
    echo        '<p><br><center>Es sind derzeit '.$players.' Spieler bei LA-LA-Land registriert!</p>';
    echo        '</div>';
    echo    '</div>';

    echo    '</div>';
    echo '<div class="col-md-4">';

    //Vehicles

    echo    "<div id='rcorners2'>";
    echo        "<div class='box-top'><center><h1>Fahrzeuge</h1></div>";
    echo        "<div class='box-panel'><p></p>";
    echo        '<p><br><center>Es sind derzeit '.$vehicles.' Fahrzeuge aktiv.</p>';
    echo        '</div>';
    echo    '</div>';

    echo    '</div>';
    echo '<div class="col-md-4">';

    //?
$money = '$'.number_format($money, 2);

    echo    '</div>';
    echo '<div class="col-lg-4">';

    echo    '</div>';
    echo '<div class="col-lg-4">';

    echo    '</div>';
    echo '<div class="col-lg-4">';


if (isset($_POST['help'])) {
    header('Location: help.php');
    die();
}
ob_end_flush();
?>

</div>
</div>
          </div>
        </div>
      </div>
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
