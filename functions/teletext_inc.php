<?php 
//
include("../inc/dashboard_config.php");

	if(!isset($_REQUEST['page']) or $_REQUEST['page'] == "") { $_REQUEST['page'] = ""; }
	if(!isset($_REQUEST['resolution']) or $_REQUEST['resolution'] == "") { $_REQUEST['resolution'] = ""; }
	if(!isset($_REQUEST['browse']) or $_REQUEST['browse'] == "") { $_REQUEST['browse'] = ""; }
	if(!isset($_REQUEST['control']) or $_REQUEST['control'] == "") { $_REQUEST['control'] = ""; }
	
	$page = $_REQUEST["page"];
	$resolution = $_REQUEST["resolution"];
	$browse = $_REQUEST["browse"];
	$control = $_REQUEST["control"];
	
	if($page !== '')
	{
	if(!is_numeric($page))
	{
    echo 'Please use numbers only..';
	exit;
	}
	
	if(strlen($page) < "3" )
	{
    echo 'Please use at least 3 chars..';
	exit;
	}
	
	$string = $page;
	for ($i=0; $i<strlen($string); $i++){
	if($string[$i] == '1'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=2'.$session_part_2; }
	if($string[$i] == '2'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=3'.$session_part_2; }
	if($string[$i] == '3'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=4'.$session_part_2; }
	if($string[$i] == '4'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=5'.$session_part_2; }
	if($string[$i] == '5'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=6'.$session_part_2; }
	if($string[$i] == '6'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=7'.$session_part_2; }
	if($string[$i] == '7'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=8'.$session_part_2; }
	if($string[$i] == '8'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=9'.$session_part_2; }
	if($string[$i] == '9'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=10'.$session_part_2; }
	if($string[$i] == '0'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=11'.$session_part_2; }
	
	$sendTeletext_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($sendTeletext_request);
	sleep(2);
	}
	echo '<img src="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/grab?format=jpg&r='.$resolution.'&&o=&n=&'.$time.$session_part_2.'">';
	}
	
	//
	if($browse !== '')
	{
	if($browse == 'page_backward'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=108'.$session_part_2; }
	if($browse == 'page_forward'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=103'.$session_part_2; }
	if($browse == 'underpage_backward'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=105'.$session_part_2; }
	if($browse == 'underpage_forward'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=106'.$session_part_2; }
	
	$sendTeletext_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($sendTeletext_request);
	sleep(2);
	
	echo '<img src="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/grab?format=jpg&r='.$resolution.'&&o=&n=&'.$time.$session_part_2.'">';
	}
	
	if($control !== '')
	{
	if($control == 'on'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=388'.$session_part_2; }
	if($control == 'off'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=174'.$session_part_2; }
	if($control == 'reload'){ $xmlfile = ''.$url_format.'://'.$box_ip.'/web/remotecontrol?command=388'.$session_part_2; }

	if($control == 'restart')
	{	
	$xmlfile = $url_format.'://'.$box_ip.'/web/remotecontrol?command=174'.$session_part_2; 
	$sendTeletext_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($sendTeletext_request);
	sleep(2);
	
	$xmlfile = $url_format.'://'.$box_ip.'/web/remotecontrol?command=388'.$session_part_2;
	$sendTeletext_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($sendTeletext_request);
	
	sleep(3);
	}
	//
	
	$sendTeletext_request = @file_get_contents($xmlfile, false, $webrequest);
	$xml = simplexml_load_string($sendTeletext_request);
	sleep(2);
	
	if($control == 'on'){ sleep(5); }
	if($control == 'off'){ echo 'Teletext off..'; exit; }
	if($control == 'reload'){ sleep(3); }
	
	echo '<img src="'.$url_format.'://'.$box_user.':'.$box_password.'@'.$box_ip.'/grab?format=jpg&r='.$resolution.'&&o=&n=&'.$time.$session_part_2.'">';
	}

?>