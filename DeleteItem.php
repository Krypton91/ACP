<?php
error_reporting(0);
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

include 'verifyPanel.php';
masterconnect();
$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];
logIt($user, "hat ein Item aus dem PlayerInv gelöscht!", $dbcon);
$sql = "DELETE FROM user_items WHERE id='$_POST[id]'";
mysqli_query($dbcon, $sql);

?>