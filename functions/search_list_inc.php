<?php 
//
	if(!isset($_REQUEST['delete_id']) or $_REQUEST['delete_id'] == "") { $_REQUEST['delete_id'] = "";
	
	} else {
	
	include("../inc/dashboard_config.php");
	
	$_REQUEST['delete_id'] = $_REQUEST['delete_id']; 
	
	$delete_id = $_REQUEST['delete_id'];
	
	$sql = mysqli_query($dbmysqli, "DELETE FROM `saved_search` WHERE id = '".$delete_id."' ");
	
	sleep(1);
	header('Content-Type: text/event-stream');
	header('Cache-Control: no-cache');
	echo "data: deleted\n\n";
	exit;
	}
	
	// get record locations
	$sql2 = "SELECT * FROM `record_locations` ORDER BY id ASC";
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {	
	{
	if(!isset($rec_dropdown_saved_search_list) or $rec_dropdown_saved_search_list == "") { $rec_dropdown_saved_search_list = ""; } else { $rec_dropdown_saved_search_list = $rec_dropdown_saved_search_list; }
	$rec_dropdown_saved_search_list = $rec_dropdown_saved_search_list."<option value=\"$obj->e2location\">$obj->e2location</option>"; }
	}
	}
	
	// channel dropdown
	$sql2 = "SELECT * FROM `channel_list` ORDER BY e2servicename ASC";
	if ($result2 = mysqli_query($dbmysqli,$sql2))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result2)) {
	
	$servicename_enc = rawurldecode($obj->servicename_enc);
	
	{
	if(!isset($channel_dropdown_saved_search_list) or $channel_dropdown_saved_search_list == "") { $channel_dropdown_saved_search_list = ""; } else { $channel_dropdown_saved_search_list = $channel_dropdown_saved_search_list; }
	$channel_dropdown_saved_search_list = $channel_dropdown_saved_search_list."<option value=\"$obj->e2servicereference\">$servicename_enc</option>"; }
	}
	}
	//
	
	$sql = "SELECT * FROM `saved_search` ORDER BY id DESC";
	if ($result=mysqli_query($dbmysqli,$sql))
	{
	// Fetch one and one row
	while ($obj = mysqli_fetch_object($result)) {
	
	if(!isset($saved_search_list) or $saved_search_list == "") { $saved_search_list = ""; } else { $saved_search_list = $saved_search_list; }
	
	$servicename_enc = rawurldecode($obj->servicename_enc);
	$searchterm = rawurldecode($obj->searchterm);
	
	if ($obj->activ == 'yes' ){ $as = 'success'; } else { $as = 'danger'; }
	
	if ($obj->e2eventservicename == 'NULL'){ $obj->e2eventservicename = 'all Channels'; }
	
	if(strlen($searchterm) > "25" ) {
	$searchterm = substr($searchterm, 0, 25);
	$searchterm = $searchterm . '...';
	$searchterm2 = rawurldecode($obj->searchterm);
	} else {
	$searchterm = rawurldecode($obj->searchterm);
	$searchterm2 = $searchterm;
	}
	
	$saved_search_list = $saved_search_list."
	<div id=\"search_list_div_$obj->id\">
	<div id=\"saved_search_list\">
	  <div class=\"row\">
		<div id=\"saved_search_list_$obj->id\" onclick=\"saved_search_list_edit(this.id);\" style=\"cursor: pointer;\">
		  <div class=\"col-md-3\">
			<p>Searchterm<br>
			$searchterm</p>
		  </div>
		  <div class=\"col-md-2\">
			<p>Searcharea<br>
			$obj->search_option</p>
		  </div>
		  <div class=\"col-md-3\">
			<p>Channel<br>
			  $servicename_enc</p>
		  </div>
		  <div class=\"col-md-2\">
			<p>Record location<br>
			  $obj->e2location</p>
		  </div>
		  <div class=\"col-md-1\">
			<p>activ<br>
			<span class=\"badge-$as\">$obj->activ</span></p>
		  </div>
		</div>
	  </div>
	  <!---->
	  <div id=\"saved_search_list_div_$obj->id\" style=\"display:none;\">
	  <div class=\"row\">
	  <div class=\"col-md-3\">
			<p><input id=\"searchterm_$obj->id\" type=\"text\" value=\"$searchterm2\" class=\"search_list_term\"><p>
		  </div>
		  <div class=\"col-md-2\">
			<p><select id=\"searcharea_$obj->id\" class=\"search_list_area\">
			<option value=\"\"></option>
			<option value=\"all\">all</option>
			<option value=\"title\">title</option>
			<option value=\"description\">description</option>
			<option value=\"extdescription\">extended description</option>
		  </select></p>
		  </div>
		  <div class=\"col-md-3\">
			<p><select id=\"channel_dropdown_saved_search_list_$obj->id\" class=\"search_list_channel\">
			<option value=\"\"></option>
			<option value=\"NULL\">All channels</option>
			$channel_dropdown_saved_search_list</select></p>
		  </div>
		  <div class=\"col-md-2\">
			<p><select id=\"rec_dropdown_saved_search_list_$obj->id\" value=\"$obj->e2location\" class=\"search_list_rec_location\"><option value=\"\"></option>
			$rec_dropdown_saved_search_list
			</select></p>
		  </div>
		  <div class=\"col-md-1\">
			<p><select id=\"active_$obj->id\" class=\"search_list_active\">
			<option value=\"\"></option>
			<option value=\"yes\">yes</option>
			<option value=\"no\">no</option>
		  </select></p>
		  </div>
		  </div>
		<input id=\"saved_search_list_save_btn_$obj->id\" type=\"submit\" onClick=\"saved_search_list_save(this.id)\" value=\"SAVE\" class=\"btn btn-success\">
		<input id=\"saved_search_list_delete_btn_$obj->id\" type=\"submit\" onClick=\"saved_search_list_delete(this.id)\" value=\"DELETE\" class=\"btn btn-danger\"/>
		<span id=\"saved_search_list_status_$obj->id\"></span>
		<div class=\"spacer_5\"></div>
		</div>
	</div>
	<div class=\"spacer_10\"></div>
	<!-- ROW -->
	</div>";
	}
}
if(!isset($saved_search_list) or $saved_search_list == "") { $saved_search_list = "No saved searches..<hr />"; } else { $saved_search_list = $saved_search_list; }
?>