<?php 
//
	include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST["action"]) or $_REQUEST["action"] == ""){ $_REQUEST["action"] = ""; }
	if(!isset($_REQUEST["service_reference"]) or $_REQUEST["service_reference"] == ""){ $_REQUEST["service_reference"] = ""; }
	
	$action = $_REQUEST['action'];
	$service_reference = $_REQUEST['service_reference'];
	
	if($action == "change_channel")
	{
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `channel_list` WHERE `e2servicereference` LIKE '".$service_reference."' ");
	$result = mysqli_fetch_assoc($sql);
	
	echo "<a href=\"$url_format://$box_ip/web/stream.m3u?ref=".$result['e2servicereference']."&name=".$result['servicename_enc']."\" target=\"_blank\" title=\"Stream\">
	<i class=\"fa fa-desktop fa-1x\"></i></a>";
	
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `qp_selected` = '0' ");
	mysqli_query($dbmysqli, "UPDATE `channel_list` SET `qp_selected` = '1' WHERE `e2servicereference` LIKE '".$service_reference."' ");
	exit;
	}
	
	// channel dropdown
	$sql = "SELECT * FROM `channel_list` ORDER BY `".$sort_quickpanel."` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	{
	
	if($obj->qp_selected == "1"){ $select = "selected=\"selected\""; } else { $select = ""; }
			
	if(!isset($dropdown) or $dropdown == ""){ $dropdown = ""; }
	
	$dropdown = $dropdown.utf8_encode('<option value="'.$obj->e2servicereference.'" '.$select.'>'.$obj->e2servicename.'</option>');
	}
	}
	}
	
	// panel content
	$sql = mysqli_query($dbmysqli, "SELECT e2servicereference, servicename_enc FROM `channel_list` WHERE `qp_selected` = '1'");
	
	if (mysqli_num_rows($sql) == 0){ $sql = mysqli_query($dbmysqli, "SELECT e2servicereference, servicename_enc FROM `channel_list` ORDER BY `".$sort_quickpanel."` ASC"); }
	
	if($result = mysqli_fetch_assoc($sql))
	{
	echo '
	<div class="quickpanel_dropdown">
	<select id="quickpanel_dropdown" class="form-control" onChange="quickpanel(\'change\');">'.$dropdown.'</select>
	</div>
	<div class="row">
	<div class="col-lg-4 col-md-4 col-xs-5"></div>
	<div class="col-md-6">
	<div class="quickpanel_icons">
	<div id="quickpanel_stream_icon" class="row1">
	<a href="'.$url_format.'://'.$box_ip.'/web/stream.m3u?ref='.$result['e2servicereference'].'&name='.$result['servicename_enc'].'" target="_blank" title="Stream">
	<i class="fa fa-desktop fa-1x"></i></a>
	</div>
	<div class="row2">
	  <a style="cursor:pointer;" title="Show EPG" onClick="quickpanel(\'epg\')">
	  <i class="fa fa-list-alt fa-1x"></i></a>
	</div>
	<div class="row3">
	  <a style="cursor:pointer;" title="Zap to channel" onClick="quickpanel(\'zap\')">
	  <i class="fa fa-arrow-up fa-1x"></i></a>
	  <span id="quickpanel_status"></span>
	</div>
	<div style="clear:both"></div>
	</div>
	</div><!-- col-->
	</div><!--row-->
	';
	}

?>
