<!DOCTYPE html>
<html>
<head>
<script src="js/jquery-ui.min.js" type="text/javascript"></script>
<script src="js/tag-it.min.js" type="text/javascript"></script>
<script>
$(function(){
	$('#tag_list*').tagit();
    $("#saved_search_list*").hover(function(){
	$(this).css("background-color", "#FAFAFA");
    }, function(){
    $(this).css("background-color", "white");
  });
});
</script>
<link href="assets/css/jquery.tagit.css" rel="stylesheet" type="text/css">
<link href="assets/css/tagit.ui-zendesk.css" rel="stylesheet" type="text/css">
</head>
<body>
<?php 
//
include("../inc/dashboard_config.php");
	
	if(!isset($_REQUEST['delete_id']) or $_REQUEST['delete_id'] == "") { $_REQUEST['delete_id'] = "";
	
	} else {
	
	$delete_id = $_REQUEST['delete_id'];
	
	$sql = mysqli_query($dbmysqli, "DELETE FROM `saved_search` WHERE `id` = '".$delete_id."' ");
	
	sleep(1);
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data:deleted";
	exit;
	}
	
	// sort saved search
	if(!isset($_REQUEST['sort_list']) or $_REQUEST['sort_list'] == ""){ 
	
	$sortby = $search_list_sort; 
	
	} else {
	
	$sort_list = $_REQUEST['sort_list'];
	$sql = mysqli_query($dbmysqli, "UPDATE `settings` SET `search_list_sort` = '".$sort_list."' ");
	$sortby = $sort_list;
	}
	
	if($sortby == 'id'){ $sortby1 = 'DESC'; }
	if($sortby == 'searchterm'){ $sortby1 = 'ASC'; }
	
	// get record locations
	$sql2 = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_saved_search_list) or $rec_dropdown_saved_search_list == "") { $rec_dropdown_saved_search_list = ""; }
	$rec_dropdown_saved_search_list = $rec_dropdown_saved_search_list."<option value=\"$obj->e2location\">$obj->e2location</option>"; }
	}
	}
	
	// channel dropdown
	$sql2 = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	while ($obj = mysqli_fetch_object($result2)) {
	
	$servicename_enc = rawurldecode($obj->servicename_enc);
	
	{
	if(!isset($channel_dropdown_saved_search_list) or $channel_dropdown_saved_search_list == "") { $channel_dropdown_saved_search_list = ""; }
	$channel_dropdown_saved_search_list = $channel_dropdown_saved_search_list."<option value=\"$obj->e2servicereference\">$servicename_enc</option>"; }
	}
	}
	//
	
	$sql = "SELECT * FROM `saved_search` ORDER BY ".$sortby." ".$sortby1." ";
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($saved_search_list) or $saved_search_list == "") { $saved_search_list = ""; }
	
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$searchterm = rawurldecode($obj->searchterm);
	$search_option = $obj->search_option;
	$exclude_channel = rawurldecode($obj->exclude_channel);
	$exclude_title = rawurldecode($obj->exclude_title);
	$exclude_description = rawurldecode($obj->exclude_description);
	$exclude_extdescription = rawurldecode($obj->exclude_extdescription);
	$record_location = $obj->e2location;
	$activ = $obj->activ;
	$rec_replay = $obj->rec_replay;
	
	if(!isset($exclude_channel) or $exclude_channel == "") { $exclude_channel = ""; }
	if(!isset($exclude_title) or $exclude_title == "") { $exclude_title = ""; }
	if(!isset($exclude_description) or $exclude_description == "") { $exclude_description = ""; }
	if(!isset($exclude_extdescription) or $exclude_extdescription == "") { $exclude_extdescription = ""; }
	if(!isset($rec_replay) or $rec_replay == "") { $rec_replay = "off"; }
	
	if($rec_replay == 'on'){ $selected4 = 'selected'; } else { $selected4 = ''; }
	if($rec_replay == 'off'){ $selected5 = 'selected'; } else { $selected5 = ''; }
	
	if($search_option == 'all'){ $selected6 = 'selected'; } else { $selected6 = ''; }
	if($search_option == 'title'){ $selected7 = 'selected'; } else { $selected7 = ''; }
	if($search_option == 'description'){ $selected8 = 'selected'; } else { $selected8 = ''; }
	if($search_option == 'extdescription'){ $selected9 = 'selected'; } else { $selected9 = ''; }
	
	if($activ == 'yes'){ $selected10 = 'selected'; } else { $selected10 = ''; }
	if($activ == 'no'){ $selected11 = 'selected'; } else { $selected11 = ''; }
	
	if($activ == 'yes'){ $as = 'success'; } else { $as = 'danger'; }
	
	if($obj->e2eventservicename == 'NULL'){ $servicename_enc = 'All Channels'; }
		
	if(strlen($searchterm) > "25" )
	{
	$searchterm = substr($searchterm, 0, 25);
	$searchterm = $searchterm . '...';
	$searchterm2 = rawurldecode($obj->searchterm);
	} else {
	$searchterm = rawurldecode($obj->searchterm);
	$searchterm2 = $searchterm;
	}
	
	if($obj->e2eventservicereference == 'NULL')
	{
	$search_channel = '';
	$channel_id = '';
	
	} else {
	
	$search_channel = 'on';
	$channel_id = $obj->e2eventservicereference; 
	}
	
	if($search_option == "all"){ $search_option_desc = "All"; }
	if($search_option == "title"){ $search_option_desc = "Title"; }
	if($search_option == "description"){ $search_option_desc = "Description"; }
	if($search_option == "extdescription"){ $search_option_desc = "Extended description"; }
	
	if($time_format == "1")
	{
	$last_change = date("d.m - H:i", $obj->last_change);
	$last_crawl = date("d.m - H:i", $obj->last_crawl);
	//
	if($last_change == "01.01 - 01:00"){ $last_change = "not changed"; }
	if($last_crawl == "01.01 - 01:00"){ $last_crawl = "not crawled"; }
	}
	
	if($time_format == "2" or $time_format == "")
	{
	$last_change = date("n/d - g:i A", $obj->last_change);
	$last_crawl = date("n/d - g:i A", $obj->last_crawl);
	//
	if($last_change == "1/01 - 1:00 AM"){ $last_change = "not changed"; }
	if($last_crawl == "1/01 - 1:00 AM"){ $last_crawl = "not crawled"; }
	}
	
	// get record location id
	$sql = mysqli_query($dbmysqli, "SELECT * FROM `record_locations` WHERE `e2location` = '".$record_location."' LIMIT 0,1");
	$result3 = mysqli_fetch_assoc($sql);
	$rec_location_id = $result3['id'];
	
	$saved_search_list = $saved_search_list."
	<div id=\"search_list_div_$obj->id\">
	<div id=\"saved_search_list\">
	  <div class=\"row\">
		<div id=\"saved_search_list_$obj->id\" onclick=\"saved_search_list_edit(this.id);\" style=\"cursor: pointer;\">
		  <div class=\"col-md-3\">
			<p><span style=\"color:#ccc\">Term</span><br>
			$searchterm</p>
		  </div>
		  <div class=\"col-md-2\">
			<p>Area<br>
			$search_option_desc</p>
		  </div>
		  <div class=\"col-md-3\">
			<p>Channel<br>
			  $servicename_enc</p>
		  </div>
		  <div class=\"col-md-2\">
			<p>Record location<br>
			  $obj->e2location</p>
		  </div>
		  <div class=\"col-md-2\">
			<p>activ<br>
			<span class=\"badge-$as\">$obj->activ</span></p>
		  </div>
		</div>
	  </div>
	  <!---->
	  <div id=\"saved_search_list_div_$obj->id\" style=\"display:none;\">
	  	<div class=\"row\">
			<div class=\"col-md-3\">
			<p><input id=\"searchterm_$obj->id\" type=\"text\" value=\"$searchterm2\" class=\"search_list_term\" title=\"$searchterm2\"></p>
		  </div>	  
		  <div class=\"col-md-2\">
			<p><select id=\"searcharea_$obj->id\" class=\"search_list_area\">
			<option value=\"all\" $selected6>all</option>
			<option value=\"title\" $selected7>title</option>
			<option value=\"description\" $selected8>description</option>
			<option value=\"extdescription\" $selected9>ext. description</option>
		  </select></p>
		  </div>
		  <!---->
		  <div class=\"col-md-3\">
			<p><select id=\"channel_dropdown_saved_search_list_$obj->id\" class=\"search_list_channel\">
			<option value=\"\"></option>
			<option value=\"NULL\">All channels</option>
			$channel_dropdown_saved_search_list</select></p>
		  </div>
		  <!---->
		  <div class=\"col-md-2\">
			<p><select id=\"rec_dropdown_saved_search_list_$obj->id\" value=\"$obj->e2location\" class=\"search_list_rec_location\">
			<option value=\"\"></option>
			$rec_dropdown_saved_search_list
			</select></p>
		  </div>
		  <div class=\"col-md-2\">
			<p><select id=\"active_$obj->id\" class=\"search_list_active\">
			<option value=\"yes\" $selected10>yes</option>
			<option value=\"no\" $selected11>no</option>
		  </select></p>
		  </div>
		  </div>
		  <div class=\"row\">
		  <div class=\"col-md-3\">
			<p>Timer for repeating Broadcast's<br><select id=\"rec_replay_$obj->id\" class=\"search_list_replay\">
			<option value=\"yes\" $selected4>yes</option>
			<option value=\"no\" $selected5>no</option>
		  </select></p>
		  </div> 
		  </div><!-- row -->
		<div class=\"row\">
		<div class=\"col-md-12\">
		  <p><a id=\"show_all_$obj->id\" onclick=\"show_all_exclude_fields(this.id)\" style=\"cursor:pointer;\">Edit excluded term(s)</a>
		  <span id=\"exclude_nav_$obj->id\" style=\"display:none;\">
		  <span class=\"exclude_channel\" id=\"exclude_channel_no_$obj->id\" onclick=\"show_exclude_channel(this.id)\">Channel</span>
		  <span class=\"exclude_title\" id=\"exclude_title_no_$obj->id\" onclick=\"show_exclude_title(this.id)\">Title</span>
		  <span class=\"exclude_description\" id=\"exclude_description_no_$obj->id\" onclick=\"show_exclude_description(this.id)\">Description</span>
		  <span class=\"exclude_extdescription\" id=\"exclude_extdescription_no_$obj->id\" onclick=\"show_exclude_extdescription(this.id)\">Ext. description</span>
		  </span></p>
		  </div><!--col-->
		  </div><!--row-->
		  <div id=\"exclude_channel_field_$obj->id\" class=\"row\" style=\"display:none;\">
		  <div id=\"channel_ul\" class=\"col-md-12\">
		  <input name=\"exclude_channel_$obj->id\" class=\"exclude_channel_field\" id=\"tag_list\" value=\"$exclude_channel\">
		  </div><!--col 12-->
		  </div><!-- row-->
		  <div id=\"exclude_title_field_$obj->id\" class=\"row\" style=\"display:none;\">
		  <div id=\"title_ul\" class=\"col-md-12\">
		  <input name=\"exclude_title_$obj->id\" id=\"tag_list\" value=\"$exclude_title\">
		  </div><!--col 12-->
		  </div><!-- row-->
		  <div id=\"exclude_description_field_$obj->id\" class=\"row\" style=\"display:none;\">
		  <div id=\"description_ul\" class=\"col-md-12\">
		  <input name=\"exclude_description_$obj->id\" id=\"tag_list\" value=\"$exclude_description\">
		  </div><!--col 12-->
		  </div><!-- row-->
		  <div id=\"exclude_extdescription_field_$obj->id\" class=\"row\" style=\"display:none;\">
		  <div id=\"extdescription_ul\" class=\"col-md-12\">
		  <input name=\"exclude_extdescription_$obj->id\" id=\"tag_list\" value=\"$exclude_extdescription\">
		  </div><!--col 12-->
		  </div><!-- row-->
		  <div class=\"row\">
		  <div class=\"col-md-12\">
		  <a href=\"search.php?searchterm=$obj->searchterm&option=$search_option&record_location=$rec_location_id&exclude_channel=$obj->exclude_channel&exclude_title=$obj->exclude_title&exclude_description=$obj->exclude_description&exclude_extdescription=$obj->exclude_extdescription&search_channel=$search_channel&channel_id=$channel_id&rec_replay=$rec_replay&search_id=$obj->id\" target=\"_blank\" title=\"Show results\">
		  <i class=\"fa fa-search fa-1x\"></i></a>
		<input id=\"saved_search_list_save_btn_$obj->id\" type=\"submit\" onClick=\"saved_search_list_save(this.id)\" value=\"SAVE\" class=\"btn btn-success btn-sm\">
		<input id=\"saved_search_list_delete_btn_$obj->id\" type=\"submit\" onClick=\"saved_search_list_delete(this.id)\" value=\"DELETE\" class=\"btn btn-danger btn-sm\"/>
		<span id=\"saved_search_list_scroll_timer_$obj->id\"></span>
		<span id=\"saved_search_list_status_$obj->id\"></span>
		<div class=\"crawl-info\"><span aria-hidden=\"true\">Last change: <span id=\"last_change_$obj->id\">$last_change</span> | Last crawl: $last_crawl</span></div>
		<div class=\"spacer_5\"></div>
		</div>
	</div>
	<!-- ROW -->
	</div>
	</div>
	<div class=\"spacer_10\"></div>
	</div>
	";
	}
}
if(!isset($saved_search_list) or $saved_search_list == ""){ $saved_search_list = "No saved searches..<hr />"; } else { echo $saved_search_list; }
?>
</body>
</html>
