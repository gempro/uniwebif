<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['action']) or $_REQUEST['action'] == ''){ $_REQUEST['action'] = ''; }
	$action = $_REQUEST['action'];
	
	if($action == 'show')
	{
	
	$sql = "SELECT * FROM `ignore_list` ORDER BY `e2eventtitle` ASC";
	
	if ($result = mysqli_query($dbmysqli,$sql))
	{
	while ($obj = mysqli_fetch_object($result)){
	{
	$id = $obj->id;
	$e2eventtitle = $obj->e2eventtitle;
	$e2eventdescription = $obj->e2eventdescription;
	$search_term = rawurldecode($obj->search_term);
	
	if(!isset($ignore_list) or $ignore_list == ''){ $ignore_list = ''; }
	if($e2eventdescription == ''){ $spacer_ed = ''; } else { $spacer_ed = ' | '; }
	if($search_term == ''){ $spacer_st = ''; } else { $spacer_st = ' | '; }
	
	// count items
	$sql_1 = mysqli_query($dbmysqli, 'SELECT COUNT(hash) FROM `ignore_list` ');
	$result_1 = mysqli_fetch_row($sql_1);
	$summary = $result_1[0];

	$ignore_list_header = '
	<div class="row">
	<div class="col-md-2">Total: '.$summary.'</div>
	<div class="col-md-10"></div>
	<div class="spacer_10"></div>
	</div>';
	
	$ignore_list = $ignore_list. "
	<div class=\"row\" id=\"ignore_list_cnt_$id\">
	<div class=\"col-md-10\">
	<div style=\"border:1px solid; border-color:#ccc; padding-left: 3px;\">$e2eventtitle$spacer_ed$e2eventdescription$spacer_st<b>$search_term</b></div> 
	</div>
	<div class=\"col-md-2\">
	<input id=\"ignore_list_btn_$id\" type=\"submit\" onClick=\"ignore_list_delete(this.id)\" value=\"Delete\" class=\"btn btn-xs btn-default\"/>
	<span id=\"ignore_list_status_$id\"></span>
	</div>
	<div class=\"spacer_10\"></div>
	</div><!--row-->
	";
	}
    }
	}
	
	echo utf8_encode($ignore_list_header.$ignore_list);
	exit; 
	}
	
	//
	if($action == 'delete')
	{
	$id = $_REQUEST["id"];
	
	mysqli_query($dbmysqli, 'DELETE FROM `ignore_list` WHERE `id` = "'.$id.'" ');
	
	sleep(1);
	
	echo 'deleted';
	
	exit;
}
?>

</body>
</html>
