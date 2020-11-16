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

$sql = "SELECT * FROM `accounts` WHERE `id` = $_POST[uid]";
$result = mysqli_query($dbcon, $sql);
$player = $result->fetch_object();

$password = $player->password;
$hwid = $player->hwid;
$IsWhiteListed = $player->isWhitelisted;
$isBanned = $player->isBanned;
$admin = $player->adminlevel;

if ($player->playerid != '' || $player->pid != '') {
    if ($player->playerid == '') {
        $pid = $player->pid;
    } else {
        $pid = $player->playerid;
    }
}

//Hash das passwort.
function HashPassword($clearPW)
{
    $newPassword = hash('sha256', $clearPW);
    //echo $newPassword;
    return $newPassword;
}
switch ($_POST['column']) {
    case 'password':
        $password = logs($staffPerms['money'], 'password', $pid, $user, $dbcon, $player, $_POST['editval']);
        $test = HashPassword($password);
        $UpdateQ = "UPDATE accounts SET $_POST[column]='$test' WHERE id='$_POST[uid]'";
    break;
    case 'hwid':
        $hwid = logs($staffPerms['money'], 'hwid', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE accounts SET $_POST[column]='$hwid' WHERE id='$_POST[uid]'";
    break;
    case 'isWhitelisted':
        $IsWhiteListed = logs($staffPerms['money'], 'isWhitelisted', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE accounts SET $_POST[column]='$IsWhiteListed' WHERE id='$_POST[uid]'";
    break;
    case 'isBanned':
        $isBanned = logs($staffPerms['money'], 'isBanned', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE accounts SET $_POST[column]='$isBanned' WHERE id='$_POST[uid]'";
    break;
    case 'adminlevel':
        $adminlevel = logs($staffPerms['IG-Admin'], 'adminlevel', $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE players SET $_POST[column]='$adminlevel' WHERE uid='$_POST[uid]'";
    break;
    case 'donatorlvl':
        $donatorlvl = logs($staffPerms['editPlayer'], $_POST['column'], $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE players SET $_POST[column]='$donatorlvl' WHERE uid='$_POST[uid]'";
    break;
    case 'donorlevel':
        $donorlevel = logs($staffPerms['editPlayer'], $_POST['column'], $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE players SET $_POST[column]='$donorlevel' WHERE uid='$_POST[uid]'";
    break;
    case 'blacklist':
        $blacklist = logs($staffPerms['editPlayer'], $_POST['column'], $pid, $user, $dbcon, $player, $_POST['editval']);
        $UpdateQ = "UPDATE players SET $_POST[column]='$blacklist' WHERE uid='$_POST[uid]'";
    break;

    default:
        $message = 'ERROR';
        logIt($user, $message, $dbcon);
    break;
}

mysqli_query($dbcon, $UpdateQ);
