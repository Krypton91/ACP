<?PHP

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];
$EditorText = $_POST['editval'];
include '../verifyPanel.php';
masterconnect();


logIt("SYSTEM", "AddNote Triggert! text im Editor war: " + $EditorText, $dbcon);
?>