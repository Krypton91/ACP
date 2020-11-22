<?php

session_start();

$staffPerms = $_SESSION['perms'];

if ($staffPerms['superUser'] != '1') {
    header('Location: lvlError.php');
    die();
}

$fail = false;
  if ($_POST['user'] != '') {
      $user = $_POST['user'];
  } else {
      echo 'error?';
      $fail = true;
  }

  if ($_POST['pass'] != '') {
      $pass = $_POST['pass'];
  } else {
      echo 'error?';
      $fail = true;
  }

  if ($_POST['host'] != '') {
      $host = $_POST['host'];
  } else {
      echo 'error?';
      $fail = true;
  }

  if ($_POST['name'] != '') {
      $name = $_POST['name'];
  } else {
      echo 'error?';
      $fail = true;
  }

if (!$fail) {
    $filename = 'verifyPanel.php';
    $ourFileName = $filename;
    $ourFileHandle = fopen($ourFileName, 'w');

    $written = '<?php

include "functions.php";

function masterconnect(){

global '.'$'.'dbcon;
'.'$'."dbcon = mysqli_connect('$host', '$user', '$pass', '$name') or die ('Database connection failed');
}

function loginconnect(){

global ".'$'.'dbconL;
'.'$'."dbconL = mysqli_connect('$host', '$user', '$pass', '$name');
}

function Rconconnect(){

global ".'$'.'rcon;
'.'$'."rcon = new \Nizarii\ArmaRConClass\ARC('$RHost', $RPort, '$RPass');
}

global ".'$'.'DBHost;
'.'$'."DBHost = '$host';
global ".'$'.'DBUser;
'.'$'."DBUser = '$user';
global ".'$'.'DBPass;
'.'$'."DBPass = '$pass';
global ".'$'.'DBName;
'.'$'."DBName = '$name';
?>
";

    fwrite($ourFileHandle, $written);
    fclose($ourFileHandle);

    header('Location: settings.php');
    die();
} else {
    echo $fail;
}
