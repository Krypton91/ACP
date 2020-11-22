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


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
          <div id="alert-area"></div>
		  <form action='editPlayer.php' method='post'>



<div class="modal fade bd-example-modal-lg" id='myModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Player Inventory</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
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





 <!-- Add Support Tiocket Modal -->
<div class="modal fade bd-example-modal-lg" id='AddNoteModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Notiz hinzufügen</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

        <div class='panel panel-info'>
            <div class='panel-heading'>
            </div>
            <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Begründung:
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
              <form action="addNote.php" method="post">
                <textarea class="textarea" name="textarea" placeholder="NICHTS "
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
              </form>
              <button type="Submit" class="btn btn-block btn-primary" onClick="safeNote(textarea.Value);">Hinzufügen</button>
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
        </div>
    </div>
  </div>
</div>
</div>





<!-- UpdateBank -->
<div class="modal fade bd-example-modal-lg" id='EditBankModul' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Konto</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

        <div class='panel panel-info'>
            <div class='panel-heading'>
            </div>
            <section class="content">
      <div class="row">
        <div class="col-md-12">
          <div class="card card-outline card-info">
            <div class="card-header">
              <h3 class="card-title">
                Begründung:
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
              <form method="post"> 
              <input id="probleminput" class="form-control" onBlur="dbSave(this.value, '<?php echo $player->id; ?>', 'amount', '<?php echo $bankAccount->amount; ?>')"; type=text value= "<?php echo $bankAccount->amount; ?>" >
              <button type="submit" class="btn btn-block btn-primary" onclick="UpdateBank(probleminput.Value, $player->id, $bankAccount->amount)">Ändern</button>
              </form> 
              </div>
            </div>
          </div>
        </div>
        <!-- /.col-->
      </div>
      <!-- ./row -->
    </section>
        </div>
    </div>
  </div>
</div>
</div>

 <!-- Main content -->
 <section class="content">
    <div class="container-fluid">
    <!-- Main row -->
    <div class="row">
            <!-- Left col -->
            <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3>Bank Konto</h3>

                <p><?php echo "$bankAccount->amount $" ?></p>
              </div>
              <div class="icon">
              <i class="fas fa-university"></i>
              </div>
              <a data-toggle="modal" data-target="#EditBankModul" class="small-box-footer">Edit <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3>Bargeld</h3>
                <p><?php echo "$player->money $" ?></p>
              </div>
              <div class="icon">
              <i class="fas fa-money-bill"></i>
              </div>
              <a href="#" class="small-box-footer">Edit <i class="fas fa-arrow-circle-right"></i></a>
          </div>
          
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>Registriert  seit</h3>
                <p><?php echo "$account->createdAt" ?></p>
              </div>
              <div class="icon">
              <i class="fas fa-clock"></i>
              </div>
              <a href="#" class="small-box-footer">Information</a>
          </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>Whitelisted:</h3>
                <p><?php
                if($account->isWhitelisted == 1)
                      echo "Yes!";
                else
                    echo "No!";
                  ?>
                 </p>
              </div>
              <div class="icon">
              <i class="fas fa-clock"></i>
              </div>
              <a href="#" class="small-box-footer">Whitelist</a>
          </div>
          </div>
          </div>
        <div class="row">
          <section class="col-lg-3 col-6">
              <!-- Custom tabs (Charts with tabs)-->
              <div class="card">
                <div class="card-header">
                <div class="btn-group" role="group" aria-label="...">
                  <h3 class="card-title">
                  <i class="fas fa-star"></i>
                    INFO
                  </h3>
                 
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                    <div class="card-body">
                      <?php
                      //FETCH INFO RETURN FROM DB! Selenski da musst du es ändern! :-)
                      //Online -> Offline Return wenn ein bild dann hier! :)
                      echo '</br>';
                      if($player->isOnline == 'Y')
                      {
                          echo '<center>';
                          echo '<small class="badge badge-success"></i>Online</small>';
                      }
                      else
                      {
                          //echo '<style="color:blue"> <center><h4>Status: Offline</h3></center>>';
                          echo '<center>';
                          echo '<small class="badge badge-danger"></i>Offline</small>';
                      }
                      echo '<center><b>Player since: </b>'.$account->createdAt.'</center>';
                      echo '</br>';
                      echo '</br>';
                      echo '<center><b>SocialClub: </b>'.$account->socialClub.'</center>';
                      echo '<center><b>First Name: </b>'.$player->ingameName.'</center>';
                      //Essen Trinken Leben
                      echo '</br>';
                      echo '<center>Essen: '.$player->food.'</center>';
                      echo '<center>Trinken: '.$player->drink.'</center>';
                      echo '<center>Leben: '.$player->health.'</center>';

                      echo '</div>';
                      //PlayerNotes wenn Icons für edit usw hier ändern!
                      
                  ?>
              </ul>
            </div><!-- /.card-header -->
          </section>
          

          <!-- right col (We are only adding the ID to make the widgets sortable)-->
          <section class="col-lg-5 connectedSortable">

            <!-- Custom tabs (Charts with tabs)-->
              <div class="card">
                <div class="card-header">
                <div class="btn-group" role="group" aria-label="...">
                  <h3 class="card-title">
                  <i class="fas fa-star"></i>
                    Support
                  </h3>
                  </div>
                  </br>
                  </br>
                   <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
		              <input class = 'btn btn-primary btn-outline' type='submit' name='remove' value='Vehicles'>
                   <!-- Button END -->
	   	            </div>
                   <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
		              <button type="button" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#myModal">Edit Gear</button>
                   <!-- Button END -->
	   	            </div>
                   <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
		              <input class = 'btn btn-primary btn-outline' type='submit' name='remove' value='Ban'>
                   <!-- Button END -->
	   	            </div>
                  <div class="card-tools">
                    <ul class="nav nav-pills ml-auto">
                    <div class="card-body">
                    <?php
                      //PlayerNotes wenn Icons für edit usw hier ändern!
                      
                  ?>
              </ul>
            </div><!-- /.card-header -->
            <!-- /.card -->
            </div>
          </div>
          </div>
          </div>
          </div>


          <div class="col-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Support-Info</h3>
                <div class="card-tools">
                    <div class="input-group-append">
                      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#AddNoteModal"><i class="fas fa-plus"></i></button>
                     
                    </div>
                  </div>
                </div>
              <!-- /.card-header -->
              <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                  <thead>
                    <tr>
                      <th>Bearbeitet von:</th>
                      <th>Vergehen:</th>
                      <th>Datum</th>
                    </tr>
                  </thead>
                  <tbody>
                              <?php

                              $sqlget = "SELECT * FROM reimbursement_log WHERE playerid=$player->accountId;";
                              $search_result = mysqli_query($dbcon, $sqlget) or die('Connection could not be established');

                              while ($row = mysqli_fetch_array($search_result, MYSQLI_ASSOC)) {
                                  //UFFFF 
                                  echo '<tr>';
                                  echo '<td>'.$row['staff_name'].' </td>';
                                  echo '<td>'.$row['reason'].' </td>';
                                  echo '<td>'.$row['timestamp'].' </td>';
                                  echo '</tr>';
                              }
                              ?>
                    </tr>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>
        </div>
        </div><!-- /.card-body -->
      </div>
      </div>
    <!-- /.content-wrapper -->
    </body>
<footer class="main-footer">
    <strong>Copyright &copy; 2019-2020 <a href="https://la-la-land.eu/">La-La-Land</a>.</strong>
    Developed by Krypton91 all rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 1.0
    </div>
  </footer>
<script>

function safeNote(value){
$.post('addNote.php',{editval:value},
location.reload();
    });
  };
}


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

function UpdateBank(value, uid, column, original){

        if (value != original) {
            newAlert('alert-success', 'Value Updated!');

            $.post('Backend/updateBank.php',{column:column, editval:value, uid:uid},
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


    <!-- jQuery -->
<script src="plugins/jquery/jquery.min.js"></script>
<!-- jQuery UI 1.11.4 -->
<script src="plugins/jquery-ui/jquery-ui.min.js"></script>
<!-- Resolve conflict in jQuery UI tooltip with Bootstrap tooltip -->
<script>
  $.widget.bridge('uibutton', $.ui.button)
</script>
<!-- Bootstrap 4 -->
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- ChartJS -->
<script src="plugins/chart.js/Chart.min.js"></script>
<!-- Sparkline -->
<script src="plugins/sparklines/sparkline.js"></script>
<!-- JQVMap -->
<script src="plugins/jqvmap/jquery.vmap.min.js"></script>
<script src="plugins/jqvmap/maps/jquery.vmap.usa.js"></script>
<!-- jQuery Knob Chart -->
<script src="plugins/jquery-knob/jquery.knob.min.js"></script>
<!-- daterangepicker -->
<script src="plugins/moment/moment.min.js"></script>
<script src="plugins/daterangepicker/daterangepicker.js"></script>
<!-- Tempusdominus Bootstrap 4 -->
<script src="plugins/tempusdominus-bootstrap-4/js/tempusdominus-bootstrap-4.min.js"></script>
<!-- Summernote -->
<script src="plugins/summernote/summernote-bs4.min.js"></script>
<!-- overlayScrollbars -->
<script src="plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<!-- AdminLTE App -->
<script src="dist/js/adminlte.js"></script>
<!-- AdminLTE dashboard demo (This is only for demo purposes) -->
<script src="dist/js/pages/dashboard.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="dist/js/demo.js"></script>


<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
<!-- AdminLTE for demo purposes -->
<script src="../../dist/js/demo.js"></script>
<!-- Summernote -->
<script src="../../plugins/summernote/summernote-bs4.min.js"></script>
<script>
  $(function () {
    // Summernote
    $('.textarea').summernote()
  })
</script>


  </body>
</html>
