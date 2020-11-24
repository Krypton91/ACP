<?php

session_start();
ob_start();
if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

include '../verifyPanel.php';
masterconnect();

$sql = "SELECT * FROM `bank_konten` WHERE `ownerId` = $_POST[uid]";
$result = mysqli_query($dbcon, $sql);
$player = $result->fetch_object();

$cash = $player->cash;
$bank = $player->amount;
$cop = $player->coplevel;
$medic = $player->mediclevel;
$admin = $player->adminlevel;

if ($player->playerid != '' || $player->pid != '') {
    if ($player->playerid == '') {
        $pid = $player->pid;
    } else {
        $pid = $player->playerid;
    }
}

switch ($_POST['column']) {
    case 'amount':
        $amount = logs($staffPerms['bank'], 'Bank', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE bank_konten SET $_POST[column]='$amount' WHERE ownerId='$_POST[uid]'";
    break;
    default:
        $message = 'ERROR';
        logIt($user, $message, $dbcon);
    break;
}
mysqli_query($dbcon, $UpdateQ);