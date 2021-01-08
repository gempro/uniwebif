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
	
	if(!isset($_REQUEST['delete_id']) or $_REQUEST['delete_id'] == ''){ $_REQUEST['delete_id'] = '';
	
	} else {
	
	$delete_id = $_REQUEST['delete_id'];
	
	mysqli_query($dbmysqli, "DELETE FROM `saved_search` WHERE `id` = '".$delete_id."' ");
	
	sleep(1);
	echo 'data:deleted';
	exit;
	}
	
	// sort saved search
	if(!isset($_REQUEST['sort_list']) or $_REQUEST['sort_list'] == ''){ 
	
	$sortby = $search_list_sort; 
	
	} else {
	
	$sort_list = $_REQUEST['sort_list'];
	
	mysqli_query($dbmysqli, "UPDATE `settings` SET `search_list_sort` = '".$sort_list."' ");
	
	$sortby = $sort_list;
	}
	
	if($sortby == 'id'){ $sortby1 = 'DESC'; }
	if($sortby == 'searchterm'){ $sortby1 = 'ASC'; }
	if($sortby == 'activ'){ $sortby1 = 'DESC'; }
	if($sortby == 'search_option'){ $sortby1 = 'ASC'; }
	if($sortby == 'e2location'){ $sortby1 = 'ASC'; }
	
	$group = '';
	$group_title = '';
	
	// record location
	$sql_1 = "SELECT * FROM `record_locations` ORDER BY `id` ASC";
	if ($result_1 = mysqli_query($dbmysqli,$sql_1))
	{
	while ($obj = mysqli_fetch_object($result_1)) {	
	{
	if(!isset($record_dropdown) or $record_dropdown == ''){ $record_dropdown = ''; }
	$record_dropdown = $record_dropdown.'<option value="'.$obj->e2location.'">'.$obj->e2location.'</option>'; 
	}
	}
	}
	
	// channel dropdown
	$sql_2 = "SELECT * FROM `channel_list` ORDER BY `e2servicename` ASC";
	if ($result_2 = mysqli_query($dbmysqli,$sql_2))
	{
	while ($obj = mysqli_fetch_object($result_2)) {
	$servicename_enc = rawurldecode($obj->servicename_enc);
	{
	if(!isset($channel_dropdown) or $channel_dropdown == ''){ $channel_dropdown = ''; }
	$channel_dropdown = $channel_dropdown.'<option value="'.$obj->e2servicereference.'">'.$servicename_enc.'</option>'; 
	}
	}
	}
	
	if(!isset($sort_list) or $sort_list == ''){ $sort_list = ''; }
	if($sort_list == 'searchterm' or $search_list_sort == 'searchterm')
	{
	$scroll_buttons = '
	<div id="scroll_buttons_tab">
	<div class="spacer_5"></div>
	<button onclick="search_list_scroll(\'A\')" class="btn btn-default btn-xs">A</button>
	<button onclick="search_list_scroll(\'B\')" class="btn btn-default btn-xs">B</button>
	<button onclick="search_list_scroll(\'C\')" class="btn btn-default btn-xs">C</button>
	<button onclick="search_list_scroll(\'D\')" class="btn btn-default btn-xs">D</button>
	<button onclick="search_list_scroll(\'E\')" class="btn btn-default btn-xs">E</button>
	<button onclick="search_list_scroll(\'F\')" class="btn btn-default btn-xs">F</button>
	<button onclick="search_list_scroll(\'G\')" class="btn btn-default btn-xs">G</button>
	<button onclick="search_list_scroll(\'H\')" class="btn btn-default btn-xs">H</button>
	<button onclick="search_list_scroll(\'I\')" class="btn btn-default btn-xs">I</button>
	<button onclick="search_list_scroll(\'J\')" class="btn btn-default btn-xs">J</button>
	<button onclick="search_list_scroll(\'K\')" class="btn btn-default btn-xs">K</button>
	<button onclick="search_list_scroll(\'L\')" class="btn btn-default btn-xs">L</button>
	<button onclick="search_list_scroll(\'M\')" class="btn btn-default btn-xs">M</button>
	<button onclick="search_list_scroll(\'N\')" class="btn btn-default btn-xs">N</button>
	<button onclick="search_list_scroll(\'O\')" class="btn btn-default btn-xs">O</button>
	<button onclick="search_list_scroll(\'P\')" class="btn btn-default btn-xs">P</button>
	<button onclick="search_list_scroll(\'Q\')" class="btn btn-default btn-xs">Q</button>
	<button onclick="search_list_scroll(\'R\')" class="btn btn-default btn-xs">R</button>
	<button onclick="search_list_scroll(\'S\')" class="btn btn-default btn-xs">S</button>
	<button onclick="search_list_scroll(\'T\')" class="btn btn-default btn-xs">T</button>
	<button onclick="search_list_scroll(\'U\')" class="btn btn-default btn-xs">U</button>
	<button onclick="search_list_scroll(\'V\')" class="btn btn-default btn-xs">V</button>
	<button onclick="search_list_scroll(\'W\')" class="btn btn-default btn-xs">W</button>
	<button onclick="search_list_scroll(\'X\')" class="btn btn-default btn-xs">X</button>
	<button onclick="search_list_scroll(\'Y\')" class="btn btn-default btn-xs">Y</button>
	<button onclick="search_list_scroll(\'Z\')" class="btn btn-default btn-xs">Z</button>
	<div class="spacer_10"></div>
	</div>
	';
	} else { $scroll_buttons = ''; }
	
	// saved search list
	$sql_3 = "SELECT * FROM `saved_search` ORDER BY ".$sortby." ".$sortby1." ";
	if ($result_3 = mysqli_query($dbmysqli,$sql_3))
	{
	while ($obj = mysqli_fetch_object($result_3)) {
	
	if(!isset($saved_search_list) or $saved_search_list == ""){ $saved_search_list = ""; }
	
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
	
	if(!isset($exclude_channel) or $exclude_channel == ''){ $exclude_channel = ''; }
	if(!isset($exclude_title) or $exclude_title == ''){ $exclude_title = ''; }
	if(!isset($exclude_description) or $exclude_description == ''){ $exclude_description = ''; }
	if(!isset($exclude_extdescription) or $exclude_extdescription == ''){ $exclude_extdescription = ''; }
	if(!isset($rec_replay) or $rec_replay == ''){ $rec_replay = 'off'; }
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
		
	if(strlen($searchterm) > '25')
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
	
	if($search_option == 'all'){ $search_option_desc = 'All'; }
	if($search_option == 'title'){ $search_option_desc = 'Title'; }
	if($search_option == 'description'){ $search_option_desc = 'Description'; }
	if($search_option == 'extdescription'){ $search_option_desc = 'Extended description'; }
	
	if($time_format == '1')
	{
	$last_change = date('d.m.Y - H:i', $obj->last_change);
	$last_crawl = date('d.m.Y - H:i', $obj->last_crawl);
	//
	if($obj->last_change == '0'){ $last_change = 'not changed'; }
	if($obj->last_crawl == '0'){ $last_crawl = 'not crawled'; }
	}
	
	if($time_format == '2' or $time_format == '')
	{
	$last_change = date('n/d/Y - g:i A', $obj->last_change);
	$last_crawl = date('n/d/Y - g:i A', $obj->last_crawl);
	//
	if($obj->last_change == '0'){ $last_change = 'not changed'; }
	if($obj->last_change == '0'){ $last_crawl = 'not crawled'; }
	}
	
	//
	if($sortby == 'searchterm')
	{
	if($group == strtoupper($searchterm[0][0])){ $group_title = ''; } else { $group_title = strtoupper($obj->searchterm[0][0]); }
	$group = strtoupper($obj->searchterm[0][0]);
	}
	
	// get record location id
	$sql_4 = mysqli_query($dbmysqli, "SELECT * FROM `record_locations` WHERE `e2location` = '".$record_location."' LIMIT 0,1");
	$result_4 = mysqli_fetch_assoc($sql_4);
	$rec_location_id = $result_4['id'];

	$saved_search_list = $saved_search_list."
	<div id=\"list_$group_title\">
	<div id=\"search_list_div_$obj->id\">
	<strong>$group_title</strong>
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
			<p>$obj->e2location</p>
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
			$channel_dropdown</select></p>
		  </div>
		  <!---->
		  <div class=\"col-md-2\">
			<p><select id=\"rec_dropdown_saved_search_list_$obj->id\" value=\"$obj->e2location\" class=\"search_list_rec_location\">
			<option value=\"\"></option>
			$record_dropdown
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
		  <a id=\"search_link_$obj->id\" href=\"search.php?searchterm=$obj->searchterm&option=$search_option&record_location=$rec_location_id&exclude_channel=$obj->exclude_channel&exclude_title=$obj->exclude_title&exclude_description=$obj->exclude_description&exclude_extdescription=$obj->exclude_extdescription&search_channel=$search_channel&channel_id=$channel_id&rec_replay=$rec_replay&search_id=$obj->id\" target=\"_blank\" title=\"Show results\">
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
	</div>
	";
	}
	}
	if(!isset($saved_search_list) or $saved_search_list == ""){ $saved_search_list = "No saved searches..<hr />"; } else { echo $scroll_buttons.$saved_search_list; }
?>
</body>
</html>
