<?php

include "functions.php";

function masterconnect(){

	global $dbcon;
	$dbcon = mysqli_connect('127.0.0.1', 'root', '', 'test2', '3306') or die ('Database connection failed');
}

function loginconnect(){

	global $dbconL;
	$dbconL = mysqli_connect('127.0.0.1', 'root', '', 'test2', '3306');
}

global $DBHost;
$DBHost = '127.0.0.1';
global $DBUser;
$DBUser = 'root';
global $DBPass;
$DBPass = '';
global $DBName;
$DBName = 'test2';
?>
