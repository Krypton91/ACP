<?php

error_reporting(0);
function playerID($player)
{
    if ($player->playerid != '' || $player->pid != '') {
        if ($player->playerid == '') {
            $pid = $player->pid;

            return $pid;
        }
        $pid = $player->playerid;

        return $pid;
    }
}

function remove($value)
{
    $value = replace('`', $value);
    $value = replace('"[[', $value);
    $value = replace(']]"', $value);

    return $value;
}

function replace($string, $text)
{
    return str_replace($string, '', $text);
}

function logIt($admin, $log, $dbcon)
{
    $logQ = "INSERT INTO log (user,action,level) VALUES ('$admin','$log',1)";
    mysqli_query($dbcon, $logQ);
}

function filterTable($dbcon, $sqlget)
{
    $sqldata = mysqli_query($dbcon, $sqlget);

    return $sqldata;
}
function GetPermissionForAction($PermissionGroup, $ActionPermNeed)
{
    
}
function outputSelection($max, $column, $value, $uid)
{
    ++$max;
    echo '<td>' ?>
    <select class='form-control' onChange = "dbSave(this.value, '<?php echo $uid; ?>', '<?php echo $column; ?>', '<?php echo $value; ?>' )" />
    <?php
    for ($i = 0; $i < $max; ++$i) {
        if ($value == $i) {
            echo "<option selected = 'selected'>$i</option>";
        } else {
            echo "<option>$i</option>";
        }
    }
    echo '</select></td>';
}
//Returns playername
function GetPlayerName($player)
{
    if($player->ingameName != "")
        return $player->ingameName;
    //If Request is for Bankaccount!
    if($player->beschreibung != "")
        return $player->beschreibung;
}
function logs($perms, $column, $pid, $user, $dbcon, $player, $val)
{
    if ($perms == '1') {
        if ($val != $player->$column) {
            $message = 'Admin '.$user.' has changed '.utf8_encode(GetPlayerName($player)).''.$id.''.' '.$column.' from '.$player->$column.' to '.$val;
            logIt($user, $message, $dbcon);

            $return = $val;

            return $return;
        }
    } else {
        if ($val != $player->$column) {
            $message = 'Admin '.$user.' tried to change '.utf8_encode(GetPlayerName($player)).'('.$pid.')'.' '.$column.' from '.$player->$column.' to '.$val;
            logIt($user, $message, $dbcon);

            $return = $player->$column;

            return $return;
        }
    }
}
