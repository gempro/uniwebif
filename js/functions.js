// JavaScript Document
// navbar icon scroll
function nav_icon_scroll(){
	$('html, body').animate({scrollTop : 0},800);
}

// scroll to top button
$(document).ready(function(){
	$(window).scroll(function(){
	if($(this).scrollTop() > 1000) {
	$('#scroll_top').fadeIn();
	} else {
	$('#scroll_top').fadeOut();
	}
});
$('#scroll_top').click(function(){
	$('html, body').animate({scrollTop : 0},800);
	return false;
});
});

// scroll top nav
$(document).ready(function(){
  $("a").on('click', function(event){
    if(this.hash !== "") {
      event.preventDefault();
      // Store hash
      var hash = this.hash;
      $('html, body').animate({
        scrollTop: $(hash).offset().top
      }, 800, function(){
      //window.location.hash = hash;
      });
    } // End if
  });
});

// show bouquet save btn
function open_bouquet_settings_btn(){
	animatedcollapse.show('save_bouquets_btn');
}

// open info broadcast_list_desc
function broadcast_list_desc(id){
	
	var this_id = id.replace(/broadcast_/g, "");
	animatedcollapse.addDiv('broadcast_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('broadcast_btn_'+this_id);
}

// display broadcast list main
function broadcast_main(id){
	
	$("#broadcast_main_"+id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/broadcast_list_main.php",
	{
	time: id
	},
	function(data){
	$('html, body').animate({ scrollTop: ($(broadcast_list).offset().top)}, 'slow');
	$("#broadcast_main_"+id).html(data);
	});
}

// broadcast list browse time
function broadcast_show_time(id){
	
	var time_format = $("#time_format").val();
	var hh = $("#broadcast_hh").val();
	var mm = $("#broadcast_mm").val();
	
	if(time_format == 1){
	if(isFinite(String(hh)) == false || hh == '' || hh > 23){ var hh = "00"; $("#broadcast_hh").val("00"); }
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#broadcast_mm").val("00"); }
	var am_pm = '0';
	}
	if(time_format == 2){
	if(isFinite(String(hh)) == false || hh == '' || hh > 12 || hh < 1){ var hh = "12"; $("#broadcast_hh").val("12"); }
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#broadcast_mm").val("00"); }
	var am_pm = $("#broadcast_am_pm").val();
	}
	$("#show_time").attr('data-toggle', 'tab');
	$("#broadcast_browse_time").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/broadcast_list_main.php",
	{
	time: id,
	hour: hh,
	minute: mm,
	ampm: am_pm
	},
	function(data){
	$('html, body').animate({ scrollTop: ($(broadcast_list).offset().top)}, 'slow');
	$("#broadcast_browse_time").html(data);
	});
}

// broadcast banner link
$(document).ready(function(){
    $('#broadcast_banner').click(function(e){  
    $('html, body').animate({ scrollTop: ($(broadcast_list).offset().top)}, 'slow');
    });
});

// broadcast banner hover
$(document).ready(function(){
    $("#broadcast_banner").hover(function(){
	$(this).css("background-color", "#FAFAFA");
	}, function(){
	$(this).css("background-color", "white");
    });
});

// scroll top button broadcast
$(document).ready(function(){
$(window).scroll(function(){
	if($(this).scrollTop() > 2500){
	$('#scroll_top_broadcast_list').fadeIn();
	} else {
	$('#scroll_top_broadcast_list').fadeOut();
	}
});
$('#scroll_top_broadcast_list').click(function(){
	$('html, body').animate({ scrollTop: ($(broadcast_list).offset().top)}, 'slow');
	return false;
});
});

// scroll top saved search
$(document).ready(function(){
$(window).scroll(function(){
	if($(this).scrollTop() > 1500){
	$('#scroll_top_saved_search').fadeIn();
	} else {
	$('#scroll_top_saved_search').fadeOut();
	}
});
$('#scroll_top_saved_search').click(function(){
	$('html, body').animate({ scrollTop: ($(saved_search_row).offset().top)}, 'slow');
	return false;
});
});

// scroll button size
if(document.querySelector('html').clientWidth < 400){
	var channelbrowser_btn_size = '2';
	var primetime_btn_size = '2';
	var broadcast_btn_size = '2';
	var scrolltop_btn_size = '2';
	var saved_search_btn_size = '2';
	
	} else { 
	
	var channelbrowser_btn_size = '3';
	var primetime_btn_size = '3';
	var broadcast_btn_size = '3';
	var scrolltop_btn_size = '3';
	var saved_search_btn_size = '3';
}

// navbar_header
if(document.querySelector('html').clientWidth < 830){
	var navbar_header_dashboard = '';
	var navbar_header_search = '';
	var navbar_header_timer = '';
	var navbar_header_crawl_separate = '';
	var navbar_header_settings = '';
	var navbar_header_channel_list = '';
	var navbar_header_bouquet_list = '';
	var navbar_header_ignore_list = '';
	var navbar_header_records = '';
	var navbar_header_teletext = '';
	var navbar_header_all_services = '';
	var navbar_header_setup = '';
	var navbar_header_about = '';
	
	} else {
	
	var navbar_header_dashboard = '<li class="nav-header"><div id="nav-header"><i class="fa fa-home fa-3-5x"></i></div></li>';
	var navbar_header_search = '<li class="nav-header"><div id="nav-header"><i class="fa fa-search fa-3-5x"></i></div></li>';
	var navbar_header_timer = '<li class="nav-header"><div id="nav-header"><i class="fa fa-clock-o fa-3-5x"></i></div></li>';
	var navbar_header_crawl_separate = '<li class="nav-header"><div id="nav-header"><i class="fa fa-chevron-right fa-3-5x"></i></div></li>';
	var navbar_header_settings = '<li class="nav-header"><div id="nav-header"><i class="fa fa-cog fa-3-5x"></i></div></li>';
	var navbar_header_channel_list = '<li class="nav-header"><div id="nav-header"><i class="fa fa-list fa-3-5x"></i></div></li>';
	var navbar_header_bouquet_list = '<li class="nav-header"><div id="nav-header"><i class="fa fa-list fa-3-5x"></i></div></li>';
	var navbar_header_ignore_list = '<li class="nav-header"><div id="nav-header"><i class="fa fa-list fa-3-5x"></i></div></li>';
	var navbar_header_records = '<li class="nav-header"><div id="nav-header"><i class="glyphicon glyphicon-record fa-3-5x"></i></div></li>';
	var navbar_header_teletext = '<li class="nav-header"><div id="nav-header"><i class="fa fa-globe fa-3-5x"></i></div></li>';
	var navbar_header_all_services = '<li class="nav-header"><div id="nav-header"><i class="fa fa-list fa-3-5x"></i></div></li>';
	var navbar_header_setup = '<li class="nav-header"><div id="nav-header"><i class="fa fa-wrench fa-3-5x"></i></div></li>';
	var navbar_header_about = '<li class="nav-header"><div id="nav-header"><i class="glyphicon glyphicon-question-sign fa-3-5x"></i></div></li>';
}

// statusbar
$(function(){
	statusbar_loop();
	function statusbar_loop(){
	
	$.post("functions/statusbar.php?t="+(new Date().getTime()),
	function(data){
	var obj = JSON.parse(data);
	var stream_url = decodeURIComponent(obj[0].stream_url);
	var e2servicereference = decodeURIComponent(obj[0].e2servicereference);
	var e2eventname = decodeURIComponent(obj[0].e2eventname);
	var e2eventservicename = decodeURIComponent(obj[0].e2eventservicename);
	var e2eventdescriptionextended = decodeURIComponent(obj[0].e2eventdescriptionextended);
	
	if(document.querySelector('html').clientWidth > 550){
	$("#statusbar_cnt").html("\
	<div id=\"statusbar\">\
	<div id=\"row1\">\
	<a href=\""+stream_url+"\" target=\"_blank\" title=\"Stream\">\
	<i class=\"fa fa-desktop fa-1x\"></i></a>\
	<a onclick=\"modal.open();\" title=\"Show EPG\" style=\"cursor:pointer;\"><i class=\"fa fa-list-alt fa-1x\"></i></a> "+e2eventname+"\
	| +"+obj[0].time_remaining+" of "+obj[0].time_complete+" min | <input type=\"text\" id=\"sb_service\" name='"+e2eventservicename+"' value='"+e2servicereference+"'\
	style=\"display:none;\">\
	<strong>"+e2eventservicename+"</strong></div>\
	<div id=\"row2\">"+obj[0].e2videowidth+"p x "+obj[0].e2videoheight+"p</div>\
	<div style=\"clear:both\"></div>\
	</div>");
	} else {
	$("#statusbar_cnt").html("\
	<div id=\"statusbar\">\
	<div id=\"row1\">\
	<a href='"+stream_url+"' target=\"_blank\" title=\"Stream\">\
	<i class=\"fa fa-desktop fa-1x\"></i></a>\
	<a onclick=\"modal.open();\" title=\"Show EPG\" style=\"cursor:pointer;\"><i class=\"fa fa-list-alt fa-1x\"></i></a> "+e2eventname+"\
	| <input type=\"text\" id=\"sb_service\" value='"+e2servicereference+"' style=\"display:none;\">\
	<strong>"+e2eventservicename+"</strong></div>\
	<div style=\"clear:both\"></div>\
	</div>");
	}
	
	if(obj[0].statusbar == "1"){
	animatedcollapse.addDiv('statusbar_outer', 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.show('statusbar_outer');
	}
	if(obj[0].statusbar != "1"){
	$("#statusbar_cnt").html("&nbsp;");
	animatedcollapse.addDiv('statusbar_outer', 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('statusbar_outer');
	}
	function reload_statusbar(){
	statusbar_loop(); } window.setTimeout(reload_statusbar, 60000);
	});
	}
});

// statusbar modal
$(function(){
		   
	var modal = new RModal(document.getElementById('modal'), {
	beforeOpen: function(next) {
	var e2servicereference = $("#sb_service").val();
	var service_name = $("#sb_service").attr('name');
	
	$("#sb-modal-header*").html(service_name);
	
	$.post("functions/modal_info.php",
	{
	e2servicereference: e2servicereference
	},
	function(data){
	$('#epgframe').animate({scrollTop : 0},800);
	$("#epgframe").html(data);
	next();
	});
	},
	afterOpen: function() {
	//console.log('opened');
	},
	beforeClose: function(next) {
	//console.log('beforeClose');
	next();
	},
	afterClose: function() {
	//console.log('closed');
	}
	});
	document.getElementById('modal').addEventListener('click', function(e) {
    if( e.target !== e.currentTarget ) {
	e.stopPropagation();
	return;
    }
    modal.close();
	}, false);
	document.addEventListener('keydown', function(ev) {
	modal.keydown(ev);
	}, false);
	window.modal = modal;
});

// remote control modal
$(function(){
		   
	var remote_modal = new RModal(document.getElementById('remote_modal'), {
	beforeOpen: function(next) {
	
	$.post("functions/remote_control.php",
	function(data){
	$("#rc_frame").html(data);
	next();
	});
	},
	afterOpen: function() {
	//console.log('opened');
	},
	beforeClose: function(next) {
	//console.log('beforeClose');
	next();
	},
	afterClose: function() {
	//console.log('closed');
	}
	});
	document.getElementById('remote_modal').addEventListener('click', function(e) {
    if( e.target !== e.currentTarget ) {
	e.stopPropagation();
	return;
    }
    remote_modal.close();
	}, false);
	document.addEventListener('keydown', function(ev) {
	remote_modal.keydown(ev);
	}, false);
	window.remote_modal = remote_modal;
});

// quickpanel modal
$(function(){

	var quickpanel_modal = new RModal(document.getElementById('quickpanel_modal'), {
	beforeOpen: function(next) {
	var service_reference = $("#quickpanel_dropdown").val();
	var service_name = $("#quickpanel_dropdown option:selected").text();
	
	$("#quickpanel-modal-header*").html(service_name);
	
	$.post("functions/modal_info.php",
	{
	e2servicereference: service_reference
	},
	function(data){
	$('#quickpanel_epgframe').animate({scrollTop : 0},800);
	$("#quickpanel_epgframe").html(data);
	next();
	});
	},
	afterOpen: function() {
	//console.log('opened');
	},
	beforeClose: function(next) {
	//console.log('beforeClose');
	next();
	},
	afterClose: function() {
	//console.log('closed');
	}
	});
	document.getElementById('quickpanel_modal').addEventListener('click', function(e) {
    if( e.target !== e.currentTarget ) {
	e.stopPropagation();
	return;
    }
    quickpanel_modal.close();
	}, false);
	document.addEventListener('keydown', function(ev) {
	quickpanel_modal.keydown(ev);
	}, false);
	window.quickpanel_modal = quickpanel_modal;
});

// send timer broadcast list main
function broadcast_timer(id,action){
	
	var this_id = id.replace(/broadcast_timer_btn_/g, "");
	var res = this_id.substr(3);
	var record_location = $("#rec_location_broadcast_"+this_id).val();
	var device = $("#broadcast_device_dropdown_"+this_id).val();
	
	$("#broadcast_status_timer_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_timer_instant.php",
	{
	action: action,
	hash: res,
	record_location: record_location,
	device: device
	},
	function(data){
	$("#broadcast_status_timer_"+this_id).html(data);
	});
}

// zap request broadcast list main
function broadcast_zap(id,name){
	
	var this_id = id.replace(/broadcast_zap_btn_/g, "");
	var device = $("#broadcast_device_dropdown_"+this_id).val();
	var res = this_id.substr(3);
	
	$("#broadcast_status_zap_"+this_id).fadeIn();
	$("#broadcast_status_zap_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: device
	},
	function(data){
	if(data == 'data:done'){
	$("#broadcast_status_zap_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#broadcast_status_zap_"+this_id).fadeOut(2000);
	}
	if(data == 'data:error'){ $("#broadcast_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>"); }
	});
}

//
function broadcast_change_device(id){
	
	var this_id = id.replace(/broadcast_device_dropdown_/g, "");
	var value = $("#"+id).val();
	
	$.post("functions/device_rec_location.php",
	{
	device: value
	},
	function(data){
	$("#rec_location_broadcast_"+this_id).html(data);
	});
}

// send timer searchlist
function searchlist_timer(id,action){
	
	var this_id = id.replace(/searchlist_timer_btn_/g, "");
	var record_location = $("#rec_location_searchlist_"+this_id).val();
	var device = $("#searchlist_device_dropdown_"+this_id).val();
	
	$("#searchlist_status_timer_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_timer_instant.php",
	{
	action: action,
	hash: this_id,
	record_location: record_location,
	device: device
	},
	function(data){
	$("#searchlist_status_timer_"+this_id).html(data);
	});
}

// zap request searchlist
function searchlist_zap(id,name){
	
	var this_id = id.replace(/searchlist_zap_btn_/g, "");
	var device = $("#searchlist_device_dropdown_"+this_id).val();
	
	$("#searchlist_status_zap_"+this_id+"").fadeIn();
	$("#searchlist_status_zap_"+this_id+"").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: device
	},
	function(data){
	if(data == 'data:done'){
	$("#searchlist_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#searchlist_status_zap_"+this_id+"").fadeOut(2000);
	}
	if(data == 'data:error'){ $("#searchlist_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>"); }
	});
}

//
function searchlist_change_device(id){
	
	var this_id = id.replace(/searchlist_device_dropdown_/g, "");
	var value = $("#searchlist_device_dropdown_"+this_id).val();
	
	$.post("functions/device_rec_location.php",
	{
	device: value
	},
	function(data){
	$("#rec_location_searchlist_"+this_id).html(data);
	});
}

// zap request separate channel crawler
function channel_crawler_zap(id,name){
	
	var this_id = id.replace(/channel_crawler_zap_/g, "");
	$("#channel_crawler_status_zap_"+this_id+"").fadeIn();
	$("#channel_crawler_status_zap_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: '0'
	},
	function(data){
	$("#channel_crawler_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#channel_crawler_status_zap_"+this_id+"").fadeOut(2000);
	});
}

// display all channels
function show_all(){
	
	$("#crawl_separate_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> EPG data is loading, this could take some time..");
	
	$.post("functions/channel_crawler_separate_inc.php",
	{
	channel: 'all'
	},
	function(data){
	$("#crawl_separate_list").html(data);
	});
}
// show channel panel
function show_panel(){
	animatedcollapse.toggle('single_channel_panel');
}
// display single channel
function show_single_data(){
	
	var channel = $("#channel_id").val();
	$("#crawl_separate_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_crawler_separate_inc.php",
	{
	channel: channel
	},
	function(data){
	$("#crawl_separate_list").html(data);
	});
}

// primetime banner link
$(document).ready(function(){
    $('#primetime_banner').click(function(e){
	var pt_status = $("#pt_status").html();
	if(pt_status == '1'){ $('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow'); return; }
	primetime_main('primetime_today');
	animatedcollapse.show('primetime_main_today');
    $('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow');
    });
});

// scroll top button primetime
$(document).ready(function(){
$(window).scroll(function(){
	if($(this).scrollTop() > 4000){
	$('#scroll_top_primetime_list').fadeIn();
	} else {
	$('#scroll_top_primetime_list').fadeOut();
	}
});
$('#scroll_top_primetime_list').click(function(){
	$('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow');
	return false;
	});
});

// open info primetime_list_desc
function primetime_list_desc(id){
	
	var this_id = id.replace(/primetime_/g, "");
	animatedcollapse.addDiv('primetime_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('primetime_btn_'+this_id);
}

// primetime list hover
$(document).ready(function(){
    $("#primetime_main*").hover(function(){
        $(this).css("background-color", "#FAFAFA");
        }, function(){
        $(this).css("background-color", "white");
    });
});

// display primetime list main
function primetime_main(id){
	
	var this_id = id.replace(/primetime_/g, "");
	$("#primetime_main_"+this_id+"").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$("#pt_status").html("1");
	
	$.post("functions/primetime_list_main.php",
	{
	action: 'show',
	time: this_id
	},
function(data){
	$('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow');
	$("#primetime_main_"+this_id+"").html(data);
	});
}

// send timer primetime list main
function primetime_timer(id,action){
	
	var this_id = id.replace(/primetime_timer_btn_/g, "");
	var record_location = $("#rec_location_primetime_"+this_id).val();
	var device = $("#primetime_device_dropdown_"+this_id).val();

	$("#primetime_status_timer_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/send_timer_instant.php",
	{
	action: action,
	hash: this_id,
	record_location: record_location,
	device: device
	},
	function(data){
	$("#primetime_status_timer_"+this_id).html(data);
	});
}

// zap request primetime list
function primetime_zap(id,name){
	
	var this_id = id.replace(/primetime_zap_btn_/g, "");
	var device = $("#primetime_device_dropdown_"+this_id).val();
	
	$("#primetime_status_zap_"+this_id).fadeIn();
	$("#primetime_status_zap_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: device
	},
	function(data){
	if(data == 'data:done'){
	$("#primetime_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#primetime_status_zap_"+this_id+"").fadeOut(2000);
	}
	if(data == 'data:error'){ $("#primetime_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>"); }
	});
}

// set primetime
function set_primetime(){
	
	$("#set_status").fadeIn();
	var time_format = $("#time_format").val();
	var hh = $("#primetime_hh").val();
	var mm = $("#primetime_mm").val();
	
	if(time_format == 1)
	{
	if(isFinite(String(hh)) == false || hh == '' || hh > 23){ var hh = "12"; $("#primetime_hh").val("12"); };
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#primetime_mm").val("00"); };
	var am_pm = '0';
	}
	
	if(time_format == 2)
	{
	if(isFinite(String(hh)) == false || hh == '' || hh > 12){ var hh = "12"; $("#primetime_hh").val("12"); }
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#primetime_mm").val("00"); }
	var am_pm = $("#primetime_am_pm").val();
	}
	
	$("#set_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/primetime_list_main.php",
	{
	action: 'set',
	hour: hh,
	minute: mm,
	ampm: am_pm
	},
	function(data){
	$("#set_status").html(data);
	$("#set_status").fadeOut(2000);
	});
}

//
function primetime_change_device(id){
	
	var this_id = id.replace(/primetime_device_dropdown_/g, "");
	var value = $("#"+id).val();
	
	$.post("functions/device_rec_location.php",
	{
	device: value
	},
	function(data){
	$("#rec_location_primetime_"+this_id).html(data);
	});
}

// display channelbrowser list main
function channelbrowser_main(id){
	
	var channel_id = $("#channel_id").val();
	$("#channelbrowser_main_"+id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channelbrowser_list_main.php",
	{
	time: id,
	channel: channel_id
	},
	function(data){
	$('html, body').animate({ scrollTop: ($(channelbrowser_list).offset().top)}, 'slow');
	$("#channelbrowser_main_"+id).html(data);
	});
}

// open info channelbrowser_list_desc
function channelbrowser_list_desc(id){
	
	var this_id = id.replace(/channelbrowser_/g, "");
	animatedcollapse.addDiv('channelbrowser_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('channelbrowser_btn_'+this_id);
}

// channelbrowser list hover
$(document).ready(function(){
    $("#channelbrowser_main*").hover(function(){
        $(this).css("background-color", "#FAFAFA");
        }, function(){
        $(this).css("background-color", "white");
    });
});

// channelbrowser banner link
$(document).ready(function(){
    $('#channelbrowser_banner').click(function(e) {  
    $('html, body').animate({ scrollTop: ($(channelbrowser_list).offset().top)}, 'slow');
    });
});

// scroll top button channelbrowser
$(document).ready(function(){
$(window).scroll(function(){
	if($(this).scrollTop() > 5500){
	$('#scroll_top_channelbrowser_list').fadeIn();
	} else {
	$('#scroll_top_channelbrowser_list').fadeOut();
	}
});

$('#scroll_top_channelbrowser_list').click(function(){
	$('html, body').animate({ scrollTop: ($(channelbrowser_list).offset().top)}, 'slow');
	return false;
	});
});

// send timer channelbrowser list / epg modal
function channelbrowser_timer(id,action,location){
	
	var this_id = id.replace(/channelbrowser_timer_btn_/g, "");
	var res = this_id.substr(3);
	var record_location = $("#rec_location_channelbrowser_"+this_id).val();
	var device = $("#channelbrowser_device_dropdown_"+this_id).val();

	$("#channelbrowser_status_timer_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/send_timer_instant.php",
	{
	action: action,
	hash: res,
	record_location: record_location,
	device: device
	},
	function(data){
	$("#channelbrowser_status_timer_"+this_id).html(data);
	if(location == "modal")
	{
	load_timer_list_panel();
	reload_timerlist();
	}
	});
}

// zap request channelbrowser list
function channelbrowser_zap(id,name){
	
	var this_id = id.replace(/channelbrowser_zap_btn_/g, "");
	var device = $("#channelbrowser_device_dropdown_"+this_id).val();
	var res = this_id.substr(3);
	
	$("#channelbrowser_status_zap_"+this_id+"").fadeIn();
	$("#channelbrowser_status_zap_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: device
	},
	function(data){
	if(data == 'data:done'){
	$("#channelbrowser_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#channelbrowser_status_zap_"+this_id+"").fadeOut(2000);
	}
	if(data == 'data:error'){ $("#channelbrowser_status_zap_"+this_id+"").html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>"); }
	});
}

// change target device
function channelbrowser_change_device(id){
	
	var this_id = id.replace(/channelbrowser_device_dropdown_/g, "");
	var value = $("#"+id).val();
	
	$.post("functions/device_rec_location.php",
	{
	device: value
	},
	function(data){
	$("#rec_location_channelbrowser_"+this_id).html(data);
	});
}

// open timerlist
function timerlist_desc(id){
	
	var this_id = id.replace(/timer_/g, "");
	animatedcollapse.addDiv('timerlist_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('timerlist_btn_'+this_id);
}

// timerlist delete timer
function timerlist_delete_timer(id,hash){
	
	var this_id = id.replace(/delete_timer_btn_/g, "");
	var device = $("#timerlist_device_no_"+this_id).val();
	
	if($("#timerlist_delete_db_"+this_id).is(':checked')){ var delete_from_db = '1'; } else { var delete_from_db = '0'; }
	
	$("#timerlist_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/timer_list_inc.php",
	{
	action: 'delete',
	timer_id: this_id,
	hash: hash,
	delete_from_db: delete_from_db,
	device: device
	},
	function(data){
	$("#timerlist_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer deleted");
	$("#tl_glyphicon_status_"+this_id+"").attr({style:"color:#D9534F", title:"not sent"});
	
	if(data == "data:deleted_db")
	{
	if($("#box_"+this_id).is(':checked'))
	{
	var summary = $("#selected_box_sum").text();
	var summary = summary.replace("(", "");
	var summary = summary.replace(")", "");
	var summary = summary -1;
	$("#selected_box_sum").text("("+summary+")")
	if(summary < '1'){
	function hide_selected_box_sum() { $("#selected_box_sum").fadeOut(1500);
	}
	window.setTimeout(hide_selected_box_sum, 1000);
	}
	}
	//	
	$("#box_"+this_id+"").attr({id:'null', name:'null'});
	animatedcollapse.addDiv('timerlist_div_outer_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	function hide_timerlist_div_outer() { animatedcollapse.toggle('timerlist_div_outer_'+this_id);
	}
	window.setTimeout(hide_timerlist_div_outer, 1000);
	}
	load_timer_list_panel();
	});
}

// timerlist send timer
function timerlist_send_timer(id,name,action){
	
	var this_id = id.replace(/timerlist_send_timer_btn_/g, "");
	if(action == 'zap'){ var this_id = id.replace(/timerlist_zap_timer_btn_/g, ""); }
	var record_location = $("#timerlist_rec_location_"+this_id).text();
	var device = $("#timerlist_device_no_"+name).val();
	
	if(device != '0'){
	var record_location = $("#timerlist_rec_location_device_"+name).val();
	}
	
	$("#timerlist_status_"+name).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_timer_instant.php",
	{
	action: action,
	location: 'timerlist',
	hash: this_id,
	record_location: record_location,
	device: device
	},
	function(data){
	var obj = JSON.parse(data);
	if(obj[0].conflict == '0'){
	$("#timerlist_status_"+name).html(obj[0].timer_status);
	$("#tl_glyphicon_status_"+name+"").attr({style:"color:#5CB85C", title:"sent"});
	}
	if(obj[0].conflict == '1'){
	$("#timerlist_status_"+name).html(obj[0].timer_status);
	$("#tl_glyphicon_status_"+name+"").attr({style:"color:#F0AD4E", title:"Conflict on Receiver"});
	}
	if(action == 'zap'){ $("#tl_glyphicon_status_m_"+name).attr({class:"fa fa-arrow-up fa-1x", title:"Zap timer", style:"color:#000" }); }
	if(obj[0].device_c != ''){ $("#timerlist_inner_"+name).attr({ style:"border: 1px solid "+obj[0].device_c+" !important; lol" }); }
	
	if(device != '0'){ 
	$("#timerlist_send_timer_btn_"+this_id).prop('disabled', true);
	$("#timerlist_zap_timer_btn_"+this_id).prop('disabled', true);
	$("#timerlist_hide_timer_btn_"+name).prop('disabled', true);
	$("#delete_timer_btn_"+name).prop('disabled', true);
	}
	
	load_timer_list_panel();
	});
}

//
function change_timerlist_device(id,action){
	
	var this_id = id.replace(/timerlist_device_dropdown_/g, "");
	var device_no = $("#timerlist_device_dropdown_"+this_id).val();
	var device_name = $("#timerlist_device_dropdown_"+this_id+" option:selected").text();
	
	if(device_no != 0){
	$("#rec_location_device_"+this_id).fadeIn();
	
	$.post("functions/device_rec_location.php",
	{
	location: 'timerlist',
	id: this_id,
	device: device_no
	},
	function(data){
	if(action == 'record'){
	$("#rec_location_device_"+this_id).html(data);
	}
	});
	} else { $("#rec_location_device_"+this_id).fadeOut(); }

	$("#timerlist_device_no_"+this_id).val(device_no);
}

// timerlist hide timer
function timerlist_hide_timer(id,name){
	
	var this_id = id.replace(/timerlist_hide_timer_btn_/g, "");
	var hash = name.replace(/hide_/g, "");
	
	if($("#box_"+this_id).is(':checked')){ var checked = '1'; }
	
	$("#timerlist_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/timer_list_inc.php",
	{
	action: 'hide',
	timer_id: this_id
	},
	function(data){
	function hide_timerlist_div_outer(){
	animatedcollapse.addDiv('timerlist_div_outer_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init();
	animatedcollapse.toggle('timerlist_div_outer_'+this_id);
	
	if(checked == '1'){
	var summary = $("#selected_box_sum").text();
	var summary = summary.replace("(", "");
	var summary = summary.replace(")", "");
	var summary = summary - 1;
	$("#selected_box_sum").html("("+summary+")");
	$("#box_"+this_id).attr('checked', false);
	if(summary < '1'){ $("#selected_box_sum").fadeOut(1500); }
	}
	}
	window.setTimeout(hide_timerlist_div_outer, 1000);
	$("#box_"+this_id).attr({hash:'null', id:'null'});
	function refresh_panel(){
	load_timer_list_panel();
	}
	window.setTimeout(refresh_panel, 1000);
	});
}

// timerlist unhide timer
function timerlist_unhide_timer(id){
	
	var this_id = id.replace(/timerlist_unhide_timer_btn_/g, "");
	$("#timerlist_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/timer_list_inc.php",
	{
	action: 'unhide',
	timer_id: this_id
	},
	function(data){
	$("#show-img").html(data);
	$("#timerlist_status_"+this_id).html("");
	$('#timerlist_div_outer_'+this_id).removeClass("opac_70");
	$("#timerlist_unhide_timer_btn_"+this_id).val("HIDE");
	$("#timerlist_unhide_timer_btn_"+this_id).attr({id:"timerlist_hide_timer_btn_"+this_id+"", onclick:"timerlist_hide_timer(this.id,this.name)", title:"hide Timer from list"});
	load_timer_list_panel();
	});
}

// timerlist add to inore list
function timerlist_ignore(id){
	
	var this_id = id.replace(/ignore_timer_btn_/g, "");
	$("#timerlist_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/timer_list_inc.php",
	{
	action: 'ignore',
	timer_id: this_id
	},
	function(data){
	if(data == 'data:ignored'){
	$("#timerlist_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#F0AD4E\"></i> Added to ignore list");
	}
	if(data == 'data:already_ignored'){
	$("#timerlist_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#F0AD4E\"></i> Already ignored..");
	}
	});
}

// timerlist show excluded
function timerlist_show_exclude(id){
	
	animatedcollapse.addDiv('timerlist_excluded_terms_'+id, 'fade=1,height=auto');
	animatedcollapse.init();
	animatedcollapse.toggle('timerlist_excluded_terms_'+id);	
}

// tickerlist send timer
function tickerlist_send_timer(id){

	var this_id = id.replace(/tickerlist_send_timer_btn_/g, "");
	$("#tickerlist_send_timer_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_timer_instant.php",
	{
	hash: this_id,
	location: 'ticker',
	device: '0'
	},
	function(data){
	$("#tickerlist_send_timer_status_"+this_id).html(data);
	});
}

// reload timerlist
function reload_timerlist(){
	
	$.post("functions/timer_list_inc.php",
	function(data){
	$("#selected_box_sum").html("");
	$("#panel_unhide").fadeOut();
	$("#show_unhide").attr("onclick", "timerlist_panel(this.id)");
	$("#show_unhide").attr({ title:"show"});
	$("#hidden_status").text("0");
	$("#timerlist_inc").html(data);
});
}

// timerlist panel
function select_timer_checkbox(){
	
	if(select_all.checked == true){ $("[id^=box]").prop("checked", true); }
	if(select_all.checked == false){ $("[id^=box]").prop("checked", false); }
	count_selected();
}
//
function count_selected(){
	
	var summary = document.querySelectorAll('input[id^=box]:checked').length;
	$("#selected_box_sum").html("("+summary+")");
	if(summary > '0'){ $("#selected_box_sum").fadeIn(500); }
	if(summary < '1'){ $("#selected_box_sum").fadeOut(1500); }
}
//
function timerlist_panel(id){
	
	var checked = []
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	checked.push(parseInt($(this).val()));
	});
	
	if(id == 'delete'){
	$("#del_buttons").toggle();
	return;
	}
	if(id == 'send' || id == 'delete_db' || id == 'delete_rec' || id == 'delete_both' || id == 'hide' || id == 'panel_unhide')
	{
	$("#panel_action_status").fadeIn();
	if(checked == 0){ return; }
	}
	
	if(id == 'show_unhide'){
	$("#selected_box_sum").html("");
	$("#hidden_status").text("1");
	
	$.post("functions/timer_list_inc.php",
	{
	action: 'unhide'
	},
	function(data){
	$("#panel_unhide").fadeIn();
	$("#select_all").prop("checked", false);
	$("#show_unhide").attr({onclick:"reload_timerlist()", title:"hide"});
	$("#timerlist_inc").html(data);
	});
	return;
	}
	
	$("#panel_action_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");

	var selected_timer = checked.join(';');

	$.post("functions/timer_list_panel.php",
	{
	panel_action: id,
	timer_id: selected_timer
	},
	function(data){
	
	$("#panel_action_status").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#selected_box_sum").html("");
	setTimeout(function() { $("#panel_action_status").html(""); }, 3000);
	
	if(id == 'send'){
	var obj = JSON.parse(data);
	for ($i = 0; $i <= obj.length-1; $i++)
 	{
	var t_id = obj[$i]["id"];
	var t_conflict = obj[$i]["conflict"];
	if(t_conflict == '0'){ var color = 'color:#5CB85C'; var title = 'sent'; }
	if(t_conflict == '1'){ var color = 'color:#F0AD4E'; var title = 'Conflict on Receiver'; }
	$("#tl_glyphicon_status_"+t_id).attr({style:color, title:title});
	$("#box_"+t_id).prop("checked", false);
	}
	$("#panel_action_status").fadeOut(2000);
	$("#select_all").prop("checked", false);
	load_timer_list_panel();
	}
	
	if(data == 'delete_rec_done'){
	$("#panel_action_status").fadeOut(2000);
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	$("#tl_glyphicon_status_"+$(this).val()+"").attr({style:"color:#D9534F", title:"not sent"});
	$("[id^=box]").prop("checked", false);
	});
	$("#select_all").prop("checked", false);
	load_timer_list_panel();
	}
	
	if(data == 'unhide_done'){
	$("#panel_action_status").fadeOut(2000);
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	$('#timerlist_div_outer_'+$(this).val()).removeClass("opac_70");
	$("[id^=box]").prop("checked", false);
	$("#select_all").prop("checked", false);
	$("#timerlist_unhide_timer_btn_"+$(this).val()+"").attr({value:'HIDE', onclick:'timerlist_hide_timer(this.id,this.name)', title:'hide Timer from list', id:'timerlist_hide_timer_btn_'+$(this).val()+'', name:'null'});
	});
	load_timer_list_panel();
	}
	function hide_timer_div(){
		
	if(data == 'delete_db_done' || data == 'delete_both_done' || data == 'hide_done'){
		
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{	
	animatedcollapse.addDiv('timerlist_div_outer_'+$(this).val(), 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('timerlist_div_outer_'+$(this).val());
	$("#panel_action_status").fadeOut(2000);
	$("#box_"+$(this).val()+"").attr({name:'null', id:'null'});
	});
	$("[id^=box]").prop("checked", false);
	$("#select_all").prop("checked", false);
	load_timer_list_panel();
	}
	}
	window.setTimeout(hide_timer_div, 1000);
	});
}

// display record list
function browse_records(){
	
	var storage_info_status = $("#storage_info_status").val();
	$("#record_list").fadeIn();
	var id = $("#rec_location").val();
	var device = $("#select_device").val();
	if(storage_info_status == '0'){ record_list_panel(device); }
	$("#record_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/record_list_inc.php",
	{
	device: device,
	record_location: id
	},
	function(data){
	$("#record_list").html(data);
	$("#storage_info_status").val("1")
	});
}

// open info record_list
function record_list_desc(id){

	var this_id = id.replace(/record_/g, "");
	animatedcollapse.addDiv('record_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('record_btn_'+this_id);
}

//
function reload_rec_location(){
	
	var device = $("#select_device").val();
	$("#rec_folder_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/save_rec_locations.php",
	{
	device: device
	},
	function(data){
	function refresh_page(){
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = 'records.php");';
	if(document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	window.setTimeout(refresh_page, 1000);
	});
}

// create m3u
function create_m3u(id,name){

    var record_id = $("#record_id_"+name).html();
	var device = $("#select_device").val();
	
	$("#m3u_"+name).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/create_m3u.php",
	{
	record_file: id,
	record_id: record_id,
	device: device
	},
	function(data){
	if(data == 'data:ok'){
	$("#m3u_"+name).html("<a href=\"tmp/stream-"+record_id+".m3u\">Download playlist file</a>");
	}
	});
}

// delete record
function delete_record(id,name){

	if(confirm('Are you sure?')){
	var device = $("#select_device").val();
	$("#del_status_"+name).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/record_list_inc.php",
	{
	action: 'delete',
	del_id: id,
	device: device
	},
	function(data){
	animatedcollapse.addDiv('record_entry_'+name, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('record_entry_'+name);
	record_list_panel(device);
	}
	);
	} else {
    return;
}
}

// record list panel
function record_list_panel(device){
	
	var record_location = $("#rec_location").val();
	
	$.post("functions/record_list_panel.php",
	{
	device: device,
	record_location: record_location
	},
	function(data){
	var obj = JSON.parse(data);
	$("#storage_info").html(obj[0].storage_info+'<hr>');
	$("#storage_info").fadeIn();
	$("#record_info").html("Records in folder: <strong>"+obj[0].files_summary+"</strong> | Today recorded: "+obj[0].today_summary+" | Diskspace used: "+obj[0].discspace_used+"");
	});
}

// saved search list list hover
$(document).ready(function(){
    $("#saved_search_list*").hover(function(){
	$(this).css("background-color", "#FAFAFA");
    }, function(){
    $(this).css("background-color", "white");
  });
});

// open saved search list
function saved_search_list_edit(id){
	
	var this_id = id.replace(/saved_search_list_/g, "");
	animatedcollapse.addDiv('saved_search_list_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('saved_search_list_div_'+this_id);
}

// scroll saved search
function scroll_saved_search(id,timer){
	var this_id = id.replace(/sid_/g, "");
	var timer_id = timer.replace(/timer_scroll_/g, "");
	if($("#search_list_div_"+this_id).length == 0){ return(alert("Saved search not found")); }
	$('html, body').animate({ scrollTop: ($("#search_list_div_"+this_id).offset().top -70)}, 'slow');
	animatedcollapse.addDiv('saved_search_list_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.show('saved_search_list_div_'+this_id);
	show_all_exclude_fields(this_id);
	$("#saved_search_list_scroll_timer_"+this_id).html("<input type=\"submit\" onClick=\"$('html, body').animate({scrollTop : ($(timer_"+timer_id+").offset().top-70)},800)\" value=\"BACK\" title=\"Back to timer\" class=\"btn btn-default btn-sm\"/>");
}

// edit saved search list
function saved_search_list_save(id){
	
	var this_id = id.replace(/saved_search_list_save_btn_/g, "");
	var searchterm = $("#searchterm_"+this_id).val();
	var searcharea = $("#searcharea_"+this_id).val();
	var exclude_channel = $('[name=exclude_channel_'+this_id+']').val();
	var exclude_title = $('[name=exclude_title_'+this_id+']').val();
	var exclude_description = $('[name=exclude_description_'+this_id+']').val();
	var exclude_extdescription = $('[name=exclude_extdescription_'+this_id+']').val();
	var rec_replay = $("#rec_replay_"+this_id).val();
	var channel = $("#channel_dropdown_saved_search_list_"+this_id).val();
	var record_location = $("#rec_dropdown_saved_search_list_"+this_id).val();
	var active = $("#active_"+this_id).val();

	$("#saved_search_list_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/search_list_edit.php",
	{
	action: 'save',
	id: this_id,
	searchterm: searchterm,
	searcharea: searcharea,
	exclude_channel: exclude_channel,
	exclude_title: exclude_title,
	exclude_description: exclude_description,
	exclude_extdescription: exclude_extdescription,
	rec_replay: rec_replay,
	channel: channel,
	record_location: record_location,
	active: active
	},
	function(data){
	var obj = JSON.parse(data);
	$("#last_change_"+this_id).html(obj.last_change);
	$("#saved_search_list_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#search_link_"+this_id).attr({ href:obj.search_link });
	reload_saved_search_panel();
	});
}

// saved search list delete
function saved_search_list_delete(id){
	
	if(confirm('Are you sure?')){
	var this_id = id.replace(/saved_search_list_delete_btn_/g, "");

	$("#saved_search_list_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/search_list_edit.php",
	{
	id: this_id,
	action: 'delete'
	},
	function(data){
	$("#saved_search_list_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Search deleted");
	animatedcollapse.addDiv('search_list_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	function hide_search_list_div(){ 
	animatedcollapse.toggle('search_list_div_'+this_id);
	reload_saved_search_panel();
	}
	window.setTimeout(hide_search_list_div, 1000);
	});
	} else { return; }
}

// collapse excluded fields
function show_all_exclude_fields(id){
	
	var this_id = id.replace(/show_all_/g, "");
	
	$("#exclude_nav_"+this_id).fadeIn();
	animatedcollapse.addDiv('exclude_channel_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_title_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_description_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_extdescription_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.show('exclude_channel_field_'+this_id);
	animatedcollapse.show('exclude_title_field_'+this_id);
	animatedcollapse.show('exclude_description_field_'+this_id);
	animatedcollapse.show('exclude_extdescription_field_'+this_id);
	$("#show_all_"+this_id).attr({onclick:'hide_all_exclude_fields(this.id)', title:'Hide', id:'hide_all_'+this_id });
}
//
function hide_all_exclude_fields(id){
	
	var this_id = id.replace(/hide_all_/g, "");
	
	$("#exclude_nav_"+this_id).fadeOut();
	animatedcollapse.addDiv('exclude_channel_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_title_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_description_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.addDiv('exclude_extdescription_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('exclude_channel_field_'+this_id);
	animatedcollapse.hide('exclude_title_field_'+this_id);
	animatedcollapse.hide('exclude_description_field_'+this_id);
	animatedcollapse.hide('exclude_extdescription_field_'+this_id);
	$("#hide_all_"+this_id).attr({onclick:'show_all_exclude_fields(this.id)', title:'Show', id:'show_all_'+this_id });
}
//
function show_exclude_channel(id){
	
	var this_id = id.replace(/exclude_channel_no_/g, "");
	animatedcollapse.addDiv('exclude_channel_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('exclude_channel_field_'+this_id);
}
//
function show_exclude_title(id){
	
	var this_id = id.replace(/exclude_title_no_/g, "");
	animatedcollapse.addDiv('exclude_title_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('exclude_title_field_'+this_id);
}
//
function show_exclude_description(id){
	
	var this_id = id.replace(/exclude_description_no_/g, "");
	animatedcollapse.addDiv('exclude_description_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('exclude_description_field_'+this_id);
}
//
function show_exclude_extdescription(id){
	
	var this_id = id.replace(/exclude_extdescription_no_/g, "");
	animatedcollapse.addDiv('exclude_extdescription_field_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('exclude_extdescription_field_'+this_id);
}

function crawl_channel_id(){
	
	$("#crawl_channel_id_btn").prop('disabled', true);
	$("#crawl_channel_id_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_id_crawler.php",
	function(data){
	if(data == 'data:error'){
	$("#crawl_channel_id_status").html("There was no Bouquet selected to crawl. Please check <a href=\"bouquet_list.php\">Bouquet list</a>.");
	$("#crawl_channel_id_btn").prop('disabled', false);
	}
	if(data == 'data:done'){
	function hide_status(){ 
	animatedcollapse.hide('crawl_channel_id_status');
	}
	window.setTimeout(hide_status, 2000);
	
	function hide_div_crawl_channel_id(){ 
	animatedcollapse.hide('div_crawl_channel_id');
	}
	window.setTimeout(hide_div_crawl_channel_id, 3000);

	function reset_crawl_channel_id_btn(){ 
	$("#crawl_channel_id_btn").prop('disabled', false);
	}
	window.setTimeout(reset_crawl_channel_id_btn, 3500);
	$("#crawl_channel_id_status").html("Channel ID's successfully crawled!");
	}
});
}

function crawl_complete(){
	
	$("#crawl_complete_btn").prop('disabled', true);
	
	$.post("functions/start_channel_crawler_complete.php",
	function(data){
	if(data == 'data:done'){
	$("#crawl_complete_status").html("EPG from channels successfully crawled!");
	
function hide_status(){ 
	animatedcollapse.hide('crawl_complete_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status(){ 
	$("#crawl_complete_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_crawl_complete(){ 
	animatedcollapse.hide('div_crawl_complete');
	}
	window.setTimeout(hide_div_crawl_complete, 3000);
	
function reset_crawl_complete_btn(){ 
	//document.getElementById("crawl_complete_btn").disabled = false;
	$("#crawl_complete_btn").prop('disabled', false);
	}
	window.setTimeout(reset_crawl_complete_btn, 3500);
	} // data
});
}

// crawl channel separate
function channel_crawler(id){
	
	var this_id = id.replace(/channel_crawler_/g, "");
	$("#channel_crawler_"+this_id).prop('disabled', true);	
	$("#channel_crawler_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_crawler_separate.php",
	{
	channel_hash: this_id
	},
	function(data){
	var obj = JSON.parse(data);	
	if(obj.status == 'done'){
	$("#channel_crawler_status_"+this_id).html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#channel_crawler_"+this_id).prop('disabled', false);
	$("#channel_crawler_summary_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	setTimeout(function() { $("#channel_crawler_summary_"+this_id).html(obj.summary); }, 1000);
	}
});
}

//
function crawl_saved_search(){
	
	$("#crawl_search_btn").prop('disabled', true);
	
	$.post("functions/save_timer_in_db.php",
	function(data){
	if(data == 'data:done'){
	$("#crawl_search_status").html("Timer from saved search, written in database!");
	
function hide_status(){
	animatedcollapse.hide('crawl_search_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status(){
	$("#crawl_search_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_crawl_savedsearch(){ 
	animatedcollapse.hide('div_crawl_search');
	}
	window.setTimeout(hide_div_crawl_savedsearch, 3000);
	
	function reset_crawl_search_btn(){ 
	//document.getElementById("crawl_search_btn").disabled = false;
	$("#crawl_search_btn").prop('disabled', false);
	}
	window.setTimeout(reset_crawl_search_btn, 3500);
	} // data
	});
}

//
function send_timer(){

	$("#send_timer_btn").prop('disabled', true);
	
	$.post("functions/send_timer_to_box.php",
	{
	action: 'manual'
	},
	function(data){
	if(data == 'data:done'){
	$("#send_timer_status").html("Timer was sent from database to Receiver!");
	
function hide_status(){ 
	animatedcollapse.hide('send_timer_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status(){
	$("#send_timer_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_send_timer(){ 
	animatedcollapse.hide('div_send_timer');
	}
	window.setTimeout(hide_div_send_timer, 3000);
	
function reset_send_timer_btn(){ 
	$("#send_timer_btn").prop('disabled', false);
	}
	window.setTimeout(reset_send_timer_btn, 3500);
	}
	});
}

//
function start_channelzapper(){
	
	$("#start_channelzapper_btn").prop('disabled', true);
	
	$.post("functions/channelzapper.php",
	{
	manual: 'yes'
	},
	function(data){
	if(data == 'data:done'){
	$("#channelzapper_status").html("All channels zapped!");
	
function hide_status(){ 
	animatedcollapse.hide('channelzapper_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status(){
	$("#channelzapper_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_start_channelzapper(){ 
	animatedcollapse.hide('div_start_channelzapper');
	}
	window.setTimeout(hide_div_start_channelzapper, 3000);
	
function reset_start_channelzapper_btn(){ 
	$("#start_channelzapper_btn").prop('disabled', false);
	}
	window.setTimeout(reset_start_channelzapper_btn, 3500);	
	}
	});
}

//
function save_box_settings(){
	
	var box_ip = $("#box_ip").val();
	var box_user = $("#box_user").val();
	var box_password = $("#box_password").val();
	animatedcollapse.hide('save_box_info');
	
	$("#save_box_settings_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/save_box_settings.php",
	{
	box_ip: box_ip,
	box_user: box_user,
	box_password: box_password
	},
	function(data){
	if(data == 'data: data missed'){		
	$("#save_box_settings_status").html("Data missed!");
	}
	if(data == 'data: settings ok connection ok'){
	$("#save_box_settings_status").html("Settings saved. Data connection to Receiver OK!");
	animatedcollapse.show('save_box_info');
	}
	if(data == 'data: settings ok connection error'){
	$("#save_box_settings_status").html("Settings saved, but data connection to Receiver failed. Check settings or <a href=\"help/help.html\">visit help!</a>");
	}
});
}

//
function save_settings(){
	
	var server_ip = $("#server_ip").val();
	var script_folder = $("#script_folder").val();
	var display_time_format = $("#display_time_format").val();
	
	if($("#activate_cron").is(':checked')){ var activate_cron = '1'; } else { var activate_cron = '0'; }
	
	var epg_entries_per_channel = $("#epg_entries_per_channel").val();
	var channel_entries = $("#channel_entries").val();
	var time_format = $("#time_format").val();
	var dur_down_broadcast = $("#dur_down_broadcast").val();
	var dur_up_broadcast = $("#dur_up_broadcast").val();
	var dur_down_primetime = $("#dur_down_primetime").val();
	var dur_up_primetime = $("#dur_up_primetime").val();

	if($("#epg_crawler").is(':checked')){ var epg_crawler = '1'; } else { var epg_crawler = '0'; }
	
	var crawler_hour = $("#crawler_hour").val();
	var crawler_minute = $("#crawler_minute").val();
	
	if(display_time_format == '2'){ var crawler_am_pm = $("#crawler_am_pm").val(); } else { var crawler_am_pm = '0'; }
	if($("#search_crawler").is(':checked')){ var search_crawler = '1'; } else { var search_crawler = '0'; }
	if($("#display_old_epg").is(':checked')){ var display_old_epg = '1'; } else { var display_old_epg = '0'; }
	if($("#streaming_symbol").is(':checked')){ var streaming_symbol = '1'; } else { var streaming_symbol = '0'; }
	if($("#imdb_symbol").is(':checked')){ var imdb_symbol = '1'; } else { var imdb_symbol = '0'; }
	if($("#timer_ticker").is(':checked')){ var timer_ticker = '1'; } else { var timer_ticker = '0'; }
	if($("#show_hidden_ticker").is(':checked')){ var show_hidden_ticker = '1'; } else { var show_hidden_ticker = '0'; }
	
	var ticker_time = $("#ticker_time").val();

	if($("#send_timer").is(':checked')){ var send_timer = '1'; } else { var send_timer = '0'; }
	if($("#hide_old_timer").is(':checked')){ var hide_old_timer = '1'; } else { var hide_old_timer = '0'; }
	if($("#delete_old_timer").is(':checked')){ var delete_old_timer = '1'; } else { var delete_old_timer = '0'; }
	if($("#delete_receiver_timer").is(':checked')){ var delete_receiver_timer = '1'; } else { var delete_receiver_timer = '0'; }
	if($("#delete_further_receiver_timer").is(':checked')){ var delete_further_receiver_timer = '1'; } else { var delete_further_receiver_timer = '0'; }
	if($("#dummy_timer").is(':checked')){ var dummy_timer = '1'; } else { var dummy_timer = '0'; }

	var start_epg_crawler = $("#start_epg_crawler").val();
	var after_crawl_action = $("#after_crawl_action").val();
	
	if($("#delete_old_epg").is(':checked')){ var delete_old_epg = '1'; } else { var delete_old_epg = '0'; }

	var url_format = $("#url_format").val();
	if($("#del_m3u").is(':checked')){ var del_m3u = '1'; } else { var del_m3u = '0'; }
	var sort_quickpanel = $("#sort_quickpanel").val();
	var del_time = $("#del_time").val();
	var extra_rec_time = $("#extra_rec_time").val();

	if($("#reload_progressbar").is(':checked')){ var reload_progressbar = '1'; } else { var reload_progressbar = '0'; }
	if($("#mark_searchterm").is(':checked')){ var mark_searchterm = '1'; } else { var mark_searchterm = '0'; }
	if($("#cz_activate").is(':checked')){ var cz_activate = '1'; } else { var cz_activate = '0'; }

	var cz_device = $("#cz_device").val();
	var cz_wait_time = $("#cz_wait_time").val();
	var cz_hour = $("#cz_hour").val();
	var cz_minute = $("#cz_minute").val();
	var cz_repeat = $("#cz_repeat").val();
	
	if(display_time_format == '2'){ var cz_am_pm = $("#cz_am_pm").val(); } else { var cz_am_pm = '0'; }

	var cz_start_channel = $("#channel_id").val();

	$("#save_settings_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/save_settings.php",
	{
	server_ip: server_ip,
	script_folder: script_folder,
	activate_cron: activate_cron,
	epg_entries_per_channel: epg_entries_per_channel,
	channel_entries: channel_entries,
	time_format: time_format,
	dur_down_broadcast: dur_down_broadcast,
	dur_up_broadcast: dur_up_broadcast,
	dur_down_primetime: dur_down_primetime,
	dur_up_primetime: dur_up_primetime,
	epg_crawler: epg_crawler,
	crawler_hour: crawler_hour,
	crawler_minute: crawler_minute,
	crawler_am_pm: crawler_am_pm,
	start_epg_crawler: start_epg_crawler,
	search_crawler: search_crawler,
	timer_ticker: timer_ticker,
	show_hidden_ticker: show_hidden_ticker,
	ticker_time: ticker_time,
	streaming_symbol: streaming_symbol,
	imdb_symbol: imdb_symbol,
	display_old_epg: display_old_epg,
	send_timer: send_timer,
	hide_old_timer: hide_old_timer,
	delete_old_timer: delete_old_timer,
	delete_receiver_timer: delete_receiver_timer,
	delete_further_receiver_timer: delete_further_receiver_timer,
	dummy_timer: dummy_timer,
	after_crawl_action: after_crawl_action,
	delete_old_epg: delete_old_epg,
	url_format: url_format,
	del_m3u: del_m3u,
	sort_quickpanel: sort_quickpanel,
	del_time: del_time,
	reload_progressbar: reload_progressbar,
	extra_rec_time: extra_rec_time,
	mark_searchterm: mark_searchterm,
	cz_activate: cz_activate,
	cz_device: cz_device,
	cz_wait_time: cz_wait_time,
	cz_hour: cz_hour,
	cz_minute: cz_minute,
	cz_repeat: cz_repeat,
	cz_am_pm: cz_am_pm,
	cz_start_channel: cz_start_channel
	},
	function(data){
	if(data == 'data missed'){
	$("#save_settings_status").html("Data missed!");
	}	
	if(data == 'ok'){
	$("#save_settings_status").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Settings saved");
	}
	});
}

// copy record locations from receiver
function get_receiver_data(){

	$("#save_box_info_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/save_rec_locations.php",
	{
	device: '0'
	},
	function(data){
	if(data == 'data:error'){ $("#save_box_info_status").html("An error occured.."); return; }
	if(data == 'data:done'){
	$("#save_box_info_status").html("Record locations and Bouquets has been copied!");
	function hide_settings_div(){ 
	animatedcollapse.hide('save_box_info_status');
	animatedcollapse.hide('save_box_info');
	animatedcollapse.hide('save_box_settings_status');
	}
	}
	window.setTimeout(hide_settings_div, 2000);
	
	save_bouquet_data();
	});
}

// copy bouquet urls from receiver
function save_bouquet_data(){
	
	$.post("functions/bouquet_crawler.php",
	function(data){
	function refresh_page(){
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = 'settings.php");';
	if(document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	window.setTimeout(refresh_page, 2500);
	});
}

// add/edit receiver
function device_list(id,action){
	
	if(action == 'save'){ var this_id = id.replace(/save_device_no_/g, ""); }
	if(action == 'delete'){ var this_id = id.replace(/delete_device_no_/g, ""); }
	if(action == 'save'){
	var device_description = $("#device_description_"+this_id).val();
	var device_ip = $("#device_ip_"+this_id).val();
	var device_user = $("#device_user_"+this_id).val();
	var device_password = $("#device_password_"+this_id).val();
	var device_color = $("#device_color_"+this_id).val();
	var url_format = $("#device_url_format_"+this_id).val();
	if(device_description == '' || device_ip == '' || device_user == ''){ return; }
	$("#device_list_status_"+this_id).html('<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">');
	}
	
	if(action == 'add'){
	var this_id = '';
	var device_description = $("#device_description").val();
	var device_ip = $("#device_ip").val();
	var device_user = $("#device_user").val();
	var device_password = $("#device_password").val();
	var device_record_location = $("#device_record_location").val();
	var device_color = $("#device_color").val();
	var url_format = $("#device_url_format").val();
	if(device_description == '' || device_ip == '' || device_user == ''){ return; }
	
	$("#device_list_status").fadeIn();
	$("#device_list_status").html('<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">');
	}
	
	if(action == 'delete'){ $("#device_list_status_"+this_id).html('<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">'); }
	
	$.post("functions/device_list_inc.php",
	{
	id: this_id,
	action: action,
	device_description: device_description,
	device_ip: device_ip,
	device_user: device_user,
	device_password: device_password,
	device_record_location: device_record_location,
	device_color: device_color,
	url_format: url_format
	},
	function(data){
	if(data == "data:connection_error"){
	$("#device_description").val(device_description);
	$("#device_ip").val(device_ip);
	$("#device_user").val(device_user);
	$("#device_password").val(device_password);
	$("#device_list_status_"+this_id).html("");
	$("#device_list_status").html("");
	alert("Device saved with Connection Error to Receiver");
	}
	if(data == "data:duplicate"){
	$("#device_list_status").html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>");
	$("#device_description").val(device_description);
	$("#device_ip").val(device_ip);
	$("#device_user").val(device_user);
	$("#device_password").val(device_password);
	$("#device_list_status").html("");
	alert("Device already exist");
	return; 
	}
	
	$.post("functions/device_list_inc.php",
	{
	action: 'show'
	},
	function(data){
	$("#device_list").html(data);
	$("#device_list_status").html("");
	});
	});
}

// set channel to crawl
function set_crawl_channel(id){

	var this_id = id.replace(/set_crawl_channel_/g, "");
	if($("#"+id).is(':checked')){ var set_crawler = '1'; } else { var set_crawler = '0'; }
	$("#edit_channel_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_list_inc.php",
	{
	set_crawl: set_crawler,
	channel_id: this_id
	},
	function(data){
	$("#edit_channel_"+this_id).html("");
	});
}

// channel for channelzapper
function set_zap_channel(id){
	
	var this_id = id.replace(/set_zap_channel_/g, "");
	if($("#"+id).is(':checked')){ var set_zap = '1'; } else { var set_zap = '0'; }
	$("#edit_channel_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_list_inc.php",
	{
	set_zap: set_zap,
	channel_id: this_id
	},
	function(data){
	$("#edit_channel_"+this_id).html("");
	});
}

// add channel
function add_single_channel(){

	var channel_name = $("#channel_name").val();
	var service_reference = $("#service_reference").val();
	$("#add_single_channel_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/channel_list_inc.php",
	{
	action: 'add',
	channel_name: channel_name,
	service_reference: service_reference
	},
	function(data){
	if(data == 'data:done'){
	$("#add_single_channel_status").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	}
	});
}

// select bouquet
function select_bouquet(id){
	
	var this_id = id.replace(/save_bouquet_settings_status_/g, "");
	if($("#"+id).is(':checked')){ var crawl_bouquet = '1'; } else { var crawl_bouquet = '0'; }
	$("#save_bouquet_settings_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/save_bouquet_settings.php",
	{
	crawl_bouquet: crawl_bouquet,
	bouquet_id: id
	},
	function(data){
	$("#save_bouquet_settings_status_"+this_id).html("");
	});
}

// add custom bouquet
function add_custom_bouquet(){

	var custom_bouquet_url = $("#custom_bouquet_url").val();
	var custom_bouquet_title = $("#custom_bouquet_title").val();

	$("#add_custom_bouquet_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/add_custom_bouquet.php",
	{
	custom_bouquet_url: custom_bouquet_url,
	custom_bouquet_title: custom_bouquet_title
	},
	function(data){
	$("#add_custom_bouquet_status").html(data);
	});
}

// power control
function power_control(id){
	
	$("#pc"+id).fadeIn();
	$("#pc"+id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/power_control.php",
	{
	command: id
	},
	function(data){
	if(data == 'data:true'){
	$("#pc"+id).html("<i class=\"glyphicon glyphicon-arrow-down fa-1x\" style=\"color:#D9534F\"></i>");
	$("#pc"+id).fadeOut(2000);
	}
	if(data == 'data:error'){
	$("#pc"+id).html("<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>");
	}
	if(data == 'data:false'){
	$("#pc"+id).html("<i class=\"glyphicon glyphicon-arrow-up fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#pc"+id).fadeOut(2000);
	}
	});
}

// teletext
function teletext_page(){
	
	var number = $("#page").val();
	var size = $("#size").val();
	if(number == ''){ return; }

	$("#teletext_img").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/teletext_inc.php",
	{
	page: number,
	resolution: size
	},
	function(data){
	$("#teletext_img").html(data);
	});
};

// browse page
function teletext_browse(id){
	
	var number = $("#page").val();
	var size = $("#size").val();

	$("#teletext_img").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/teletext_inc.php",
	{
	browse: id,
	resolution: size
	},
	function(data){
	$("#teletext_img").html(data);
	}
	);
};

// control
function teletext_control(id){
	
	var size = $("#size").val();
	$("#teletext_img").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");	
	
	$.post("functions/teletext_inc.php",
	{
	control: id,
	resolution: size
	},
	function(data){
	$("#teletext_img").html(data);
	});
}

// quickpanel
$(function(){
	$.post("functions/quickpanel_inc.php",
	function(data){
	$("#quickpanel_inc").html(data);
});
});

function quickpanel(action){
	
	var service_reference = $("#quickpanel_dropdown").val();
	
	$.post("functions/quickpanel_inc.php",
	{
	action: 'change_channel',
	service_reference: service_reference
	},
	function(data){
	$("#quickpanel_stream_icon").html(data);
	});
	if(action == 'epg')
	{ 
	quickpanel_modal.open();
	}
	
	if(action == 'zap')
	{ 
	$("#quickpanel_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: service_reference,
	device: '0'
	},
	function(data){
	$("#quickpanel_status").html("");
	});
	return;
	}
}

// add channel from services list
function all_services_add(id,name){
	
	var this_id = id.replace(/all_services_add_btn_/g, "");
	$("#all_services_status_add_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/services_inc.php",
	{
	action: 'add',
	id: name
	},
	function(data){
	$("#all_services_status_add_"+this_id).html(data);
	});
}

// services zap request
function all_services_zapp(id,name){
	
	var this_id = id.replace(/all_services_zapp_btn_/g, "");
	var last_zapped = $("#last_zapped_service").html();
	if(last_zapped != ''){ $("#service_list_name_"+last_zapped).attr({ style:"font-weight: normal;" }); }
	
	$("#all_services_status_zapp_"+this_id+"").fadeIn();
	$("#all_services_status_zapp_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$("#last_zapped_service").html(this_id);
	
	$.post("functions/send_zapp_request.php",
	{
	e2servicereference: name,
	device: '0'
	},
	function(data){
	$("#all_services_status_zapp_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#all_services_status_zapp_"+this_id+"").fadeOut(2000);
	$("#service_list_name_"+this_id).attr({ style:"font-weight: bold;" });
	});
}

// get services from receiver
function get_all_services(){

	$("#all_services_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> Copy Services from Receiver..");
	
	$.post("functions/services_inc.php",
	{
	action: 'crawl'
	},
	function(data){
	$.post("functions/services_inc.php",
	function(data){
	$("#all_services_list").html(data);
	});
});
}

// show services
function show_all_services(service){
	
	if(service == 'search'){ var searchterm = $("#service_searchterm").val(); } else { var searchterm = ''; }
	$("#all_services_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/services_inc.php",
	{
	service: service,
	searchterm: searchterm
	},
	function(data){
	$("#all_services_list").html(data);
	});
}

// ignore list delete
function ignore_list_delete(id){
	
	var this_id = id.replace(/ignore_list_/g, "");
	$("#ignore_list_status_"+this_id).html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/ignore_list_inc.php",
	{
	action: 'delete',
	id: this_id
	},
	function(data){
	$("#ignore_list_cnt_"+this_id).fadeOut(1000);
	});
}

function remote_control(command){

	$("#rc_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/remote_control.php",
	{
	command: command
	},
	function(data){
	$("#rc_status").html("");
	});
}
//function check_git_update(){
//	$("#update_status").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
//	$.post("functions/check_git_update.php",
//	function(data){
//	$("#update_status").html(data);
//});
//}