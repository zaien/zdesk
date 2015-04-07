<?php
include_once "../includes/globalParam.php";
include_once ROOT_DIR."/includes/dbFunctions.php";
$dbConn = dbConnect();

$head_title = "Tindakan Juruteknik - ".$_SESSION['SYSTEMPARAM']['system_name'];
$page_title = "Aduan Baru";
$page_title_desc = "Overview, stats, chat and more";
$page_title_class = "fa fa-list-alt";
$specific_css = "";
$nav_inbox = "active";
$nav_inbox_baru = "active";

$converter = new Encryption;
$lognum = $converter->decode($_GET['i']);

$sql = sprintf("SELECT A.TicketId, A.DateCreated, B.ProjectName, C.Description, D.ContactName, E.Description AS Category, F.Description AS CategorySub, A.TicketDescription, A.TicketDetails, A.ActionNote, A.TicketStatus, G.Name, A.Channel, A.Priority, A.TicketAction, H.LookupDescription AS TicketActionDesc, I.TechnicianNote, I.ActionDescription, J.LookupDescription AS TicketStatusDesc, I.DateAction FROM Ticket A 
				LEFT JOIN Project B ON B.ProjectId = A.ProjectId 
				LEFT JOIN Location C ON C.LocationId = A.LocationId 
				LEFT JOIN Contact D ON D.ContactId = A.ContactId 
				LEFT JOIN Category E ON E.CategoryId = A.CategoryId 
				LEFT JOIN CategorySub F ON F.CategorySubId = A.CategorySubId 
				LEFT JOIN User G ON G.UserId = A.TechnicianId 
				LEFT JOIN SystemLookup H ON H.LookupId = A.TicketAction AND H.LookupGroupId = 1 
				LEFT JOIN InboxAction I ON I.TicketId = A.TicketId AND I.ActionCancel IS NULL LEFT JOIN SystemLookup J ON J.LookupId = I.ActionStatus AND J.LookupGroupId = 2
				WHERE A.TicketId = %s", GetSQLValueString($lognum, "int"));
$result = dbQuery($dbConn,$sql);
$row = dbFetchAssoc($result);


//debugLog($sql);
?>

<?php include_once ROOT_DIR."/includes/header.php"; ?>

<style>
.form-control{
	text-transform: uppercase;
}

</style>
                <!-- BEGIN Breadcrumb -->
                <div id="breadcrumbs">
                
                    <ul class="breadcrumb">
                        <li>
                            <i class="fa fa-home"></i>
                            <a href="index?<?=$gl_keyid_url;?>">Utama</a>
                        </li>
                        
                    </ul>
                </div>
                <!-- END Breadcrumb -->

                <!-- BEGIN Main Content -->
                <div class="row">
                    <div class="col-md-12">
                        <div class="box box-black">
                            <div class="box-title">
                                <h3><i class="fa fa-edit"></i> Maklumat Aduan</h3>
                            </div>
                            <div class="box-content">
                              <div class="col-md-12">
								
                                        <div class="tabbable">
                                            <ul id="myTab1" class="nav nav-tabs">
                                                <li class="active"><a href="#home1" data-toggle="tab">Aduan</a></li>
                                                <li><a href="#maklumat" data-toggle="tab">Maklumat</a></li>
                                                <?php 
												if ($row['DateAction'] != "") {
												?>
                                                <li><a href="#profile1" data-toggle="tab">Tindakan</a></li>
                                                <?php } ?>
                                            </ul>

                                            <div id="myTabContent1" class="tab-content">
                                                <div class="tab-pane fade in active" id="home1">
                                                    <form class="form-horizontal form-row-separated">
                                                      <div class="form-group">
                                                          <label for="textfield2" class="col-sm-3 col-lg-2 control-label">Projek</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['ProjectName']?></p>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="password5" class="col-sm-3 col-lg-2 control-label">No. Rujukan</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=str_pad($row['TicketId'], 6, "0", STR_PAD_LEFT)?></p>
                                                          </div>
                                                      </div>
                  
                  
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Agensi</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['Description']?></p>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Tarikh</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=date("d/m/Y H:m",strtotime($row['DateCreated']))?></p>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Nama</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['ContactName']?></p>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Kategori</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?php if (isset($row['CategorySub'])){ echo $row['Category']." > ".$row['CategorySub']; } else {echo $row['Category']; }?></p>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Keterangan</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['TicketDescription']?></p>
                                                          </div>
                                                      </div>
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Terperinci</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=nl2br($row['TicketDetails'])?></p>
                                                          </div>
                                                      </div>
                  
                                                   </form>
                                                </div>
                                                <div class="tab-pane fade" id="maklumat">
                                                    <form id="frm-tutup" action="#" class="form-horizontal form-row-separated">
                                                      <div class="form-group">
                                                          <label for="textfield2" class="col-sm-3 col-lg-2 control-label">Pilihan Tindakan</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['TicketActionDesc']?></p>
                                                          </div>
                                                      </div>
                                                      
                                                      <div class="form-group">
                                                          <label for="password5" class="col-sm-3 col-lg-2 control-label">Keutamaan</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['Priority']?></p>
                                                          </div>
                                                      </div>
                  
                  
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Saluran</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['Channel']?></p>
                                                          </div>
                                                      </div>
                                                      <?php if ($row['TicketAction'] == "T") { ?>
                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Juruteknik</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=$row['Name']?></p>
                                                          </div>
                                                      </div>
                                                      <?php } ?>


                                                      <div class="form-group">
                                                          <label for="textarea5" class="col-sm-3 col-lg-2 control-label">Keterangan</label>
                                                          <div class="col-sm-9 col-lg-10 controls">
                                                              <p class="form-control-static"><?=nl2br($row['TechnicianNote'])?></p>
                                                          </div>
                                                      </div>
                                                      <?php if ($row['TicketStatusDesc'] == "TUTUP") { ?>
                                                      <div class="form-group">
                                                      		<label for="logstatus" class="col-sm-3 col-lg-2 control-label">Status</label>
                                                       		<div class="col-sm-9 col-lg-10 controls">
                                                            	<select name="logstatus" id="logstatus" class="form-control" tabindex="6" data-rule-required="true" data-msg-required="Sila isi maklumat status">
                                             						<option value="">-- SILA PILIH --</option>
                                                                	<?php
																	$s_action = dbQuery($dbConn,"SELECT * FROM SystemLookup WHERE LookupGroupId = 2");
																	while ($s_actionRS = dbFetchAssoc($s_action)){
																		$selected = "";
																		if ($row['TicketStatus'] == $s_actionRS['LookupId']) {
																			$selected = "selected=\"selected\"";
																		}
																		?>
                                                              		<option <?=$selected?>  value="<?=$s_actionRS['LookupId']?>"><?=$s_actionRS['LookupDescription']?></option>
                                                              		<?php
																	}
																	?>
                                                    			</select>
                                                          </div>
                                                      </div>
                                                      <?php } ?>
                                                      <input name="keyid" id="keyid" type="hidden" value="<?=$gl_keyid;?>" />
                                                      <input name="ticketid" id="ticketid" type="hidden" value="<?=$row['TicketId']?>" />
                                                      <input name="action" id="action" type="hidden" value="closeTicket" />
                                                   </form>
                                                </div>
                                                                                              
                                                <div class="tab-pane fade" id="profile1">
                                                	
                                                    <form id="frm-tindakan" action="#" class="form-horizontal form-row-separated">
                                                      
                                                      <div class="form-group">
                                                          <label class="col-sm-3 col-lg-2 control-label">Tarikh/Masa</label>
                                                          <div class="col-sm-5 col-lg-3 controls">
                                                             <div class="col-sm-9 col-lg-10 controls">
                                                              	<p class="form-control-static"><?=date("d/m/Y H:m", strtotime($row['DateAction']))?></p>
                                                             </div>
                                                          </div>
                                                       </div>
                                                       <div class="form-group">
                                                          <label class="col-sm-3 col-lg-2 control-label">Status</label>
                                                          <div class="col-sm-5 col-lg-3 controls">
                                                             <div class="col-sm-9 col-lg-10 controls">
                                                              	<p class="form-control-static"><?=$row['TicketStatusDesc']?></p>
                                                             </div>
                                                          </div>
                                                       </div>
                                                      <div class="form-group">
                                                          <label class="col-sm-3 col-lg-2 control-label">Tindakan</label>
                                                          <div class="col-sm-5 col-lg-3 controls">
                                                             <div class="col-sm-9 col-lg-10 controls">
                                                              	<p class="form-control-static"><?=nl2br($row['ActionDescription'])?></p>
                                                             </div>
                                                          </div>
                                                       </div>
                                                      <div class="form-group">
                                                          <label class="col-sm-3 col-lg-2 control-label">Status Batal</label>
                                                          <div class="col-sm-5 col-lg-3 controls">
                                                             <div class="col-sm-9 col-lg-10 controls">
                                                              	<select name="statusBatal" id="statusBatal" class="form-control" tabindex="6" >
                                             						<option value="">-- SILA PILIH --</option>
                                                              		<option selected="selected" value="N">TIDAK</option>
                                                                    <option value="Y">YA</option>
   
                                                    			</select>
                                                             </div>
                                                          </div>
                                                       </div>
                  									  <input name="keyid" id="keyid" type="hidden" value="<?=$gl_keyid;?>" />
                                                      <input name="ticketid" id="ticketid" type="hidden" value="<?=$row['TicketId']?>" />
                                                      <input name="action" id="action" type="hidden" value="cancelTicket" />
                                                      
                                                   </form>
                                                </div>
                                               <?php // } ?>
                                            </div>
                                        </div>

                                    </div>
                              

                              
                          </div>
                        </div>
                    </div>
                </div>
                
                
                <!--
                <br><br><br><br><br><br><br><br><br><br><br><br>
                <br><br><br><br><br><br><br><br><br><br><br><br>
                -->
                <!-- END Main Content -->     
               
<?php
$page_specific_plugin_scripts = "<script src=\"".BASE_URL."/assets/jquery-validation/dist/jquery.validate.min.js\"></script>";
$page_specific_plugin_scripts .= "<script src=\"".BASE_URL."/assets/jquery-validation/dist/additional-methods.min.js\"></script>";
$page_specific_plugin_scripts .= "<script src=\"".BASE_URL."/helpdesk/js/tindakanJuruteknik.js\"></script>";
include_once(ROOT_DIR."/includes/footer.php");

?>
