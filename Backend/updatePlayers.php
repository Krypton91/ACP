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

$sql = "SELECT * FROM `characters` WHERE `id` = $_POST[uid]";
$result = mysqli_query($dbcon, $sql);
$player = $result->fetch_object();

$cash = $player->cash;
$bank = $player->bankacc;
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
    case 'money':
        $cash = logs($staffPerms['money'], 'cash', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE characters SET $_POST[column]='$cash' WHERE id='$_POST[uid]'";
    break;
    case 'telefonnummer':
        $bankacc = logs($staffPerms['telnumber'], 'telefonnummer', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE characters SET $_POST[column]='$bankacc' WHERE id='$_POST[uid]'";
    break;
    case 'isWhitelisted':
        $coplevel = logs($staffPerms['whitelist'], 'isWhitelisted', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE characters SET $_POST[column]='$coplevel' WHERE id='$_POST[uid]'";
    break;
    case 'drivingLicense':
        $mediclevel = logs($staffPerms['driverlicense'], 'drivingLicense', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE characters SET $_POST[column]='$mediclevel' WHERE id='$_POST[uid]'";
    break;
    default:
        $message = 'ERROR';
        logIt($user, $message, $dbcon);
    break;
}

mysqli_query($dbcon, $UpdateQ);
