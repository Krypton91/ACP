<?PHP
session_start();
ob_start();
if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];


$PlayersID = $_POST['uid'];

include 'verifyPanel.php';
$EditorText = $_POST['editval'];
masterconnect();


$UpdateQ = "INSERT INTO reimbursement_log (playerid,reason,staff_name) VALUES ('$PlayersID','$EditorText','$user');";
mysqli_query($dbcon, $UpdateQ);


logIt($user, $EditorText, $dbcon);
?>