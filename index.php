<?php
session_start();

if (isset($_SESSION['failedLogin'])) {
    if ($_SESSION['failedLogin'] >= 5) {
        header('Location: locked.php');
        die();
    }
}

if (!file_exists('verifyPanel.php')) {
    header('Location: create.php');
    die();
}
?>

<html>
<head>
<title>Admin Panel - Login</title>
<link href='http://fonts.googleapis.com/css?family=Montserrat:400,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" type ="text/css" href="styles/global.css" />
<link rel="stylesheet" type ="text/css" href="styles/dashboard.css" />

<meta name="viewport" content="width=device-width, initial-scale: 1.0, user-scaleable=0" />
<!-- Insert this line above script imports  -->
<script>if (typeof module === 'object') {window.module = module; module = undefined;}</script>

<!-- normal script imports etc  -->
<script src="scripts/jquery-1.12.3.min.js"></script>
<script src="scripts/jquery.backstretch.js"></script>
<!-- Insert this line after script imports -->
<script>if (window.module) module = window.module;</script>

</head>
<body>
<meta name="viewport" content="width=device-width">
<div id="background"></div>
	<div id = "header">
        <div class ="logo"><a href="#">LA-LA-LAND<span> Admin Panel</span></a></div>
		<div class ="logoE"><a href="#"></a></div>
	</div>
<center><div id="txt"></div></center>
<div id = "Anmeldung">
<form action="login.php" method="post">
<div class="login-block">
    <h1>Login</h1>
    <input type="text" value="" placeholder="Benutzername" id="username" name="username"/>
    <input type="password" value="" placeholder="Passwort" id="password" name="password"/>
<?php

if (isset($_COOKIE['conecFail']) && $_COOKIE['conecFail'] == '1') {
    echo'<div style="color:red"><center>Datenbank ist nicht erreichbar!</center></div>';
}

if (isset($_COOKIE['fail']) && $_COOKIE['fail'] == '1') {
    echo'<div style="color:red"><center>Benutzername oder Passwort war falsch.</center></div>';
}
?>
    <button>Anmelden</button>
</div>

</form>
</div>

	<script>
        $.backstretch([
		  "images/Login_Ba.png"
        ], {
            fade: 750,
            duration: 4000
        });
    </script>
</body>
</html>
