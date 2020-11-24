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
logIt("SYSTEM", $_POST['id'], $dbcon);
$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

$sql = "DELETE FROM reimbursement_log WHERE reimbursement_id='$_POST[id]'";
mysqli_query($dbcon, $sql);

?>