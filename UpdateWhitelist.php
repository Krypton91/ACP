<?php
session_start();
ob_start();

if (!isset($_SESSION['logged'])) {
    header('Location: ../index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

include 'verifyPanel.php';
masterconnect();
$sql = "SELECT * FROM `characters` WHERE `id` = $_POST[uid]";
$result = mysqli_query($dbcon, $sql);
$player = $result->fetch_object();
$isListed = $player->isWhitelisted;
$ValueToUpdate;

if($isListed == 1){
    $ValueToUpdate = 0;
}
else
{
    $ValueToUpdate = 1;
}

if ($player->playerid != '' || $player->pid != '') {
    if ($player->playerid == '') {
        $pid = $player->pid;
    } else {
        $pid = $player->playerid;
    }
}
$coplevel = logs($staffPerms['whitelist'], 'isWhitelisted', $pid, $user, $dbcon, $player, $ValueToUpdate);
$UpdateQ = "UPDATE characters SET isWhitelisted='$coplevel' WHERE id='$_POST[uid]'";
mysqli_query($dbcon, $UpdateQ);


?>
