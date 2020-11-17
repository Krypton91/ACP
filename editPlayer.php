<?php
error_reporting(0);
session_start();
ob_start();
$version = '';

if (!isset($_SESSION['logged'])) {
    header('Location: index.php');
    die();
}

$staffPerms = $_SESSION['perms'];
$user = $_SESSION['user'];

include 'verifyPanel.php';
masterconnect();

$guidPlayer = htmlspecialchars($_POST['guid']);
$uidPlayer = mysqli_real_escape_string($dbcon, $_POST['hidden']);

$sql = "SELECT * FROM `characters` WHERE id='$uidPlayer'";
$result = mysqli_query($dbcon, $sql);
$player = $result->fetch_object();

//Fetch For accounts
$sql1 = "SELECT * FROM `accounts` WHERE id='$player->accountId'";
$result1 = mysqli_query($dbcon, $sql1);
$account = $result1->fetch_object();


//Fetch For Bank
$sql2 = "SELECT * FROM `bank_konten` WHERE ownerId='$player->id'";
$result2 = mysqli_query($dbcon, $sql2);
$bankAccount = $result2->fetch_object();

//Fetch Items from Inventory
$sql3 = "SELECT * FROM `user_items` WHERE charid='$player->id'";
$result3 = mysqli_query($dbcon, $sql3);
$Inventory = $result3->fetch_object();


$username = utf8_encode($player->ingameName);
$pid = playerID($player);
include 'header/header.php';
?>


    <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
          <h1 style = "margin-top: 70px">Profil von: <?php echo $username;?></h1>
          <div id="alert-area"></div>
		  <form action='editPlayer.php' method='post'>
		  <div class="btn-group" role="group" aria-label="...">
		  <input class = 'btn btn-primary btn-outline' type='submit' name='remove' value='Edit bank and cash'>
	   	  </div>
          <div class="btn-group" role="group" aria-label="...">
          <input class = 'btn btn-primary btn-outline' type='submit' name='give' value='Vehicles'>
          </div>
          <input type=hidden name=hidden value= <?php echo $uidPlayer; ?> >
          <input type=hidden name=guid value= <?php echo $guidPlayer; ?> >
          <div class="btn-group" role="group" aria-label="...">
          <button type="button" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#myModal">Edit Gear</button>
          </form>
    </div>
          <br>



<div class="modal fade bd-example-modal-lg" id='myModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
      <h4 class="modal-title" id="myModalLabel">Player Inventory</h4>
    </div>
    <div class="modal-body">

        <div class='panel panel-info'>
            <div class='panel-heading'>
                <h3 class='panel-title'>Civilian Inventory</h3>
            </div>
            <div class='panel-body'>
            <table class="table table-striped" style = "margin-top: -10px">
					<th>ItemName:</th>
                    <th>Anzahl:</th>
                    <th>Benutzbar:</th>
                <?php
                    $sqlGetQueryInv = "SELECT * FROM `user_items` WHERE charid='$player->id'";
                    $search_result22 = mysqli_query($dbcon, $sqlGetQueryInv) or die('Connection could not be established');
                    
                    while ($row = mysqli_fetch_array($search_result22, MYSQLI_ASSOC)) {
                        $ItemID = $row['itemId'];
                        $sqlGetQueryInv = "SELECT * FROM `items` WHERE id='$ItemID'";

                        $result5 = mysqli_query($dbcon, $sqlGetQueryInv);
                        $Item = $result5->fetch_object();
                        $ItemName = $Item->itemName;
                        echo '<td>' ?>
                        <input class="form-control" onBlur="dbSave(this.value, '<?php echo $row['id']; ?>', 'money', '<?php echo $row['money']; ?>')"; type=text value= "<?php echo $row['money']; ?>" >
                        <?php
                        echo 'Item: '.$ItemName.' amount: '.$row['amount'];
                    }
                ?>
                </table>
            </div>
        </div>
    </div>
  </div>
</div>
</div>

<div class='panel panel-info'>
  <div class='panel-heading'>
    <h3 class='panel-title'>Player Info</h3>
  </div>
  <div class='panel-body'>
<?php
//FETCH INFO RETURN FROM DB! Selenski da musst du es ändern! :-)
echo "<div class='lic'>";
echo "<div id ='editPlayer'><center><h1>".$username.'</h1></center>';
//Online -> Offline Return wenn ein bild dann hier! :)
if($player->isOnline == 'Y')
{
    echo '<center style="color:darkgreen;text-align:center;">Online</center>';
}
else
{
    //echo '<style="color:blue"> <center><h4>Status: Offline</h3></center>>';
    echo '<center style="color:red;text-align:center;">Offline</center>';
}
echo '<center><h4>SocialClub: '.$account->socialClub.'</h3></center>';
echo '<center><h4>Player since: '.$account->createdAt.'</h3></center>';
echo '<center><h4>SocialClub: '.$account->socialClub.'</h3></center>';
echo '<center><h4>BANK: '.$bankAccount->amount.'$</h3></center>';
echo '<center><h4>CASH: '.$player->money.'$</h3></center>';
echo '<center><h4>HEALTH: '.$player->health.'</h3></center>';

echo '</div>';
echo '</div>';
//PlayerNotes wenn Icons für edit usw hier ändern!
echo "<div class='panel panel-info'>
  <div class='panel-heading'>
    <h3 class='panel-title'>Player Notes</h3>
    <input class='btn btn-primary btn-outline' type=submit name=NEW id=NEW value=NEW>
  </div>
  <div class='panel-body'>";
  ?>
            <div class="table-responsive">
            <table class="table table-striped" style = "margin-top: -10px">
              <thead>
                <tr>
					<th>Added by</th>
					<th>Note</th>
					<th>Date added</th>
                </tr>
              </thead>
              <tbody>

<?php

$sqlget = "SELECT * FROM reimbursement_log WHERE playerid=$player->accountId;";
$search_result = mysqli_query($dbcon, $sqlget) or die('Connection could not be established');

while ($row = mysqli_fetch_array($search_result, MYSQLI_ASSOC)) {
    if ($row['warning'] == 2) {
        echo '<tr class = "warning">';
    } elseif ($row['warning'] == 3) {
        echo '<tr class = "danger">';
    } else {
        echo '<tr>';
    }
    //UFFFF 
    echo '<td>'.$row['staff_name'].' </td>';
    echo '<td>'.$row['reason'].' </td>';
    echo '<td>'.$row['timestamp'].' </td>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=Delete id=Delete value=Delete".' ></td>';
    echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=Edit id=edit value=Edit".' ></td>';
    echo '</tr>';
}
echo '</table></div>';
echo '</div>';
echo '</div>';

?>


<script>

function post (id)
{
var newid = "#" + id;

	$(newid).toggleClass("btn-danger btn-success");

	$.post('Backend/changeLicense.php',{id:id,uid:'<?php echo $uidPlayer; ?>'},
	function(data)
	{


	});
}

function post1 (id)
{
var newid = "#" + id;

	 $(newid).toggleClass("btn-danger btn-success");

	var newid = id;
	$.post('Backend/changeLicense.php',{id:id,uid:'<?php echo $uidPlayer; ?>'},
	function(data)
	{

	});
}
//FUNCTIONS DONT TOUCH!!
function newAlert (type, message) {
    $("#alert-area").append($("<div class='alert " + type + " fade in' data-alert><p> " + message + " </p></div>"));
    $(".alert").delay(2000).fadeOut("slow", function () { $(this).remove(); });
}

function dbSave(value, uid, column, original){

        if (value != original) {

            newAlert('alert-success', 'Value Updated!');

            $.post('Backend/updatePlayers.php',{column:column, editval:value, uid:uid},
            function(){
                //alert("Sent values.");
            });
        };

}
</script>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="../../assets/js/vendor/jquery.min.js"><\/script>')</script>
    <script src="dist/js/bootstrap.min.js"></script>
  </body>
</html>
