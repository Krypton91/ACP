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


$VehicleIDToSearch = null;

$username = utf8_encode($player->ingameName);
$pid = playerID($player);
include 'header/header.php';


?>


<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
          <div id="alert-area"></div>
		  <form action='editPlayer.php' method='post'>


<!-- PlayerInv -->
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
            </div>
            <div class='panel-body'>
            <table class="table table-striped" style = "margin-top: -10px">
					          <th>ItemName:</th>
                    <th>Anzahl:</th>
                    <th>Action:</th>
                    <th></th>
                    </tr>
              </thead>
            <tbody>
                <?php
                    $sqlGetQueryInv = "SELECT * FROM `user_items` WHERE charid='$player->id'";
                    $search_result22 = mysqli_query($dbcon, $sqlGetQueryInv) or die('Connection could not be established');
                    
                    while ($row = mysqli_fetch_array($search_result22, MYSQLI_ASSOC)) {
                        $ItemID = $row['itemId'];
                        $sqlGetQueryInv = "SELECT * FROM `items` WHERE id='$ItemID'";

                        $result5 = mysqli_query($dbcon, $sqlGetQueryInv);
                        $Item = $result5->fetch_object();
                        $ItemName = $Item->itemName;
                         ?>
                        <?php
                          echo '<td>'.$ItemName.'</td>';
                          echo '<td>'.$row['amount'].' </td>';
                          echo '</td>';
                          echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                          echo '<td><button type="button" class="btn btn-default" value='.$row['id'].' onclick="DeleteItem(this.value);"><i class="fas fa-trash-alt"></i></button></td>';
                          echo '</form>';
                          echo '</tr>';
                    }
                ?>
                </table>
            </div>
        </div>
    </div>
  </div>
  </div>
</div>



<!-- vehicleInventory -->
<div class="modal fade bd-example-modal-lg" id='vehicleInvModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Fahrzeug Inventar</h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

        <div class='panel panel-info'>
            <div class='panel-heading'>
            </div>
            <div class='panel-body'>
            <table class="table table-striped" style = "margin-top: -10px">
					          <th>ItemName:</th>
                    <th>Anzahl:</th>
                    <th>Action:</th>
                    <th></th>
                    </tr>
              </thead>
            <tbody>
                <?php
                    $sqlGetQueryInv = "SELECT * FROM `vehicle_items` WHERE vehId='$VehicleIDToSearch'";
                    $search_result22 = mysqli_query($dbcon, $sqlGetQueryInv) or die('Connection could not be established');
                    
                    while ($row = mysqli_fetch_array($search_result22, MYSQLI_ASSOC)) {
                        $ItemID = $row['itemId'];
                        $sqlGetQueryInv = "SELECT * FROM `items` WHERE id='$ItemID'";

                        $result5 = mysqli_query($dbcon, $sqlGetQueryInv);
                        $Item = $result5->fetch_object();
                        $ItemName = $Item->itemName;
                         ?>
                        <?php
                          echo '<td>'.$ItemName.'</td>';
                          echo '<td>'.$row['amount'].' </td>';
                          echo '</td>';
                          echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                          echo '<td><button type="button" class="btn btn-default" value='.$row['id'].' onclick="FillVHID(this.value);"><i class="fas fa-trash-alt"></i></button></td>';
                          echo '</form>';
                          echo '</tr>';
                    }
                ?>
                </table>
            </div>
        </div>
    </div>
  </div>
  </div>
</div>

 <!-- Vehicle -->
<div class="modal fade bd-example-modal-lg" id='VHModal' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Fahrzeuge von: <?php echo $player->ingameName?></h4>
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">

        <div class='panel panel-info'>
            <div class='panel-heading'>
            </div>
            <div class='panel-body'>
            <table class="table table-striped" style = "margin-top: -10px">
					          <th>Fahrzeugname:</th>
                    <th>Nummernschild:</th>
                    <th>Ausgeparkt:</th>
                    <th>Beschlagnahmt:</th>
                    <th>Kaufdatum:</th>
                    <th>Garage:</th>
                    <th></th>
                    </tr>
              </thead>
            <tbody>
                <?php
                    $sqlGetQueryInv = "SELECT * FROM `vehicles` WHERE `owner`='$player->id'";
                    $search_result22 = mysqli_query($dbcon, $sqlGetQueryInv) or die('Connection could not be established');
                    
                    while ($row = mysqli_fetch_array($search_result22, MYSQLI_ASSOC)) {
                        //$ItemID = $row['itemId'];
                        //$sqlGetQueryInv = "SELECT * FROM `items` WHERE id='$ItemID'";

                        $result6 = mysqli_query($dbcon, $sqlGetQueryInv);
                        $Item = $result6->fetch_object();
                        $ItemName = $Item->itemName;
                         ?>
                        <?php
                          
                          echo '<td>'.$row['modelId'].'</td>';
                          echo '<td>'.$row['numberplate'].' </td>';
                          echo '<td>'.$row['isSpawned'].' </td>';
                          echo '<td>'.$row['isImpounded'].' </td>';
                          echo '<td>'.$row['buyDate'].' </td>';
                          echo '<td>'.$row['garage'].' </td>';
                          $VehicleIDToSearch = $row['id'];
                          echo '</td>';
                          echo '<form action=showVehicleInv.php method=post>';
                          echo '<td>'."<input class='btn btn-primary btn-outline' type=submit name=edit id=edit value=Edit".' ></td>';
                          echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                          echo "<td style='display:none;'>".'<input type=hidden name=guid value='.$return.'> </td>';
                          echo '</form>';
                          //echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['id'].'> </td>';
                          //echo '<td><button type="button" data-toggle="modal" data-target="#vehicleInvModal" data-id="'.$row['id'].'"" class="btn btn-default"><i class="fas fa-edit"></i></button></td>';
                          echo '</form>';
                          echo '</tr>';
                    }
                ?>
                </table>
            </div>
            <script>
            $(document).ready(function(){
            $('#vehicleInvModal').on('show.bs.modal', function (e) {
              var rowid = $(e.relatedTarget).data('id');
              $.ajax({
                  type : 'post',
                  url : 'showVehicleInv.php.php', //Here you will fetch records 
                  data :  'rowid='+ rowid, //Pass $id
                  success : function(data){
                  $('.fetched-data').html(data);//Show fetched data from database
                  }
        });
     });
});
            </script>
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
                <textarea id= "textarea" class="textarea" name="textarea" placeholder="NICHTS "
                          style="width: 100%; height: 200px; font-size: 14px; line-height: 18px; border: 1px solid #dddddd; padding: 10px;"></textarea>
              </form>
              <button type="button" class="btn btn-block btn-primary" onclick="UpdateNote(<?php echo $player->id; ?>);">Hinzufügen</button>
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
      <h4 class="modal-title" id="myModalLabel">Bank Account von: <?php echo $player->ingameName ?></h4>
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
                Konto:
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
              <form id="UpdateBankForm">
              <input class="form-control" onBlur="UpdateBank(this.value, '<?php echo $player->id; ?>', 'amount', '<?php echo $bankAccount->amount; ?>')"; type=text value= "<?php echo $bankAccount->amount; ?>" >
                <button type='button' name="update_confirm_bank" id="update_confirm_bank" onclick="UpdateBank();" class="btn btn-block btn-primary" >Ändern</button>
              <input type="hidden" id="column" name="column" value="amount">
              <input type="hidden" id="uid" name="uid" value="<?php $player->id?>">
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


<!-- Bargeld -->
<div class="modal fade bd-example-modal-lg" id='EditMoneyModul' tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
  <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title" id="myModalLabel">Geld von: <?php echo $player->ingameName ?></h4>
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
                Bargeld:
              </h3>
              <!-- /. tools -->
            </div>
            <!-- /.card-header -->
            <div class="card-body pad">
              <div class="mb-3">
              <form id="UpdateMoneyForm">
              <input class="form-control" onBlur="UpdateMoney(this.value, '<?php echo $player->id; ?>', 'money', '<?php echo $player->money; ?>')"; type=text value= "<?php echo $player->money; ?>" >
                <button type='button' name="update_confirm_bank" id="update_confirm_bank" onclick="UpdateMoney();" class="btn btn-block btn-primary" >Ändern</button>
              <input type="hidden" id="column" name="column" value="amount">
              <input type="hidden" id="uid" name="uid" value="<?php $player->id?>">
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

<script>
                function newAlert (type, message) {
                    $("#alert-area").append($("<div class='alert " + type + " fade in' data-alert><p> " + message + " </p></div>"));
                    $(".alert").delay(2000).fadeOut("slow", function () { $(this).remove(); });
                }
                function UpdateBank(value, uid, column, original){
                    if (value != original) {
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('Backend/updateBank.php',{column:column, editval:value, uid:uid},
                            function(){
                                location.reload();
                            });
                    };
                }

                function UpdateMoney(value, uid, column, original){
                    if (value != original) {
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('Backend/updatePlayers.php',{column:column, editval:value, uid:uid},
                            function(){
                                location.reload();
                            });
                    };
                }

                function UpdateNote(uid){
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('addNote.php',{editval:document.getElementById("textarea").value, uid:uid},
                            function(){
                                location.reload();
                            });
                }
                function DeleteNote(id){
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('RemoveNote.php',{id:id},
                            function(){
                                location.reload();
                            });
                }
                function DeleteItem(id){
                        //newAlert('alert-success', 'Erfolgreich!');
                        $.post('DeleteItem.php',{id:id},
                            function(){
                                location.reload();
                            });
                }

                function UpdateWhitelist()
                {
                   
                  $.post('UpdateWhitelist.php',{uid:<?php echo $player->id;?>},
                            function(){
                                location.reload();
                            });
                }
                function HideModalVH()
                {
                  $('#VHModal').modal('hide');
                }
            </script>

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
              <a data-toggle="modal" data-target="#EditMoneyModul" class="small-box-footer">Edit <i class="fas fa-arrow-circle-right"></i></a>
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
                if($player->isWhitelisted == 1)
                      echo "Ja!";
                else
                    echo "Nein!";
                  ?>
                 </p>
              </div>
              <div class="icon">
              <i class="fas fa-clock"></i>
              </div>
              <a onclick="UpdateWhitelist(<?php $account->isWhitelisted ?>);" class="small-box-footer">Whitelist</a>
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
                      echo '<center><b>Handynummer: </b>'.$player->telefonnummer.'</center>';
                      if($player->drivingLicense == 1)
                      {
                        echo '<center><b>Führerschein: </b> <small class="badge badge-success"></i>Ja</small></center>';
                      }
                      else
                      {
                        echo '<center><b>Führerschein: </b> <small class="badge badge-danger"></i>Nein</small></center>';
                      }
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
		              <button type="button" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#VHModal">Fahrzeuge</button>
                   <!-- Button END -->
	   	            </div>
                   <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
		              <button type="button" class="btn btn-primary btn-outline" data-toggle="modal" data-target="#myModal">Ausrüstung</button>
                   <!-- Button END -->
	   	            </div>
                  <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
		              <input class = 'btn btn-primary btn-outline' type='submit' name='remove' value='Ban'>
                  <!-- Button END -->
	   	            </div>
                   <!-- Button START -->
                  <div class="btn-group" role="group" aria-label="...">
                  <?php
                  $isActiv = 0;
                  if($player->isWhitelisted == 0)
                  {
                    ?>
                    <button type='button' name="update_confirm_bank1" id="update_confirm_bank1" onclick="UpdateWhitelist();" class="btn btn-block btn-primary" >Whitelist Hinzufügen</button>
                    <?php
                  }
                  else
                  {
                    ?>
                    <button type='button' name="update_confirm_bank2" id="update_confirm_bank2" onclick="UpdateWhitelist();" class="btn btn-block btn-primary" >Whitelist Entfernen</button>
                    <?php
                  }
                  ?>
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

                              $sqlget = "SELECT * FROM reimbursement_log WHERE playerid=$player->id;";
                              $search_result = mysqli_query($dbcon, $sqlget) or die('Connection could not be established');

                              while ($row = mysqli_fetch_array($search_result, MYSQLI_ASSOC)) {
                                  //UFFFF 
                                  echo '<tr>';
                                  echo '<td>'.$row['staff_name'].' </td>';
                                  echo '<td>'.$row['reason'].' </td>';
                                  echo '<td>'.$row['timestamp'].' </td>';
                                  echo "<td style='display:none;'>".'<input type=hidden name=hidden value='.$row['reimbursement_id'].'> </td>';
                                  echo '<td><button type="button" class="btn btn-default" value='.$row['reimbursement_id'].' onclick="DeleteNote(this.value);"><i class="fas fa-trash-alt"></i></button></td>';
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
</script>
</body>
</html>
