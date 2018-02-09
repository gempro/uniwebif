// JavaScript Document
// navbar icon scroll
	function nav_icon_scroll() {
	$('html, body').animate({scrollTop : 0},800);
}

// scroll to top button
$(document).ready(function(){
	$(window).scroll(function(){
		if ($(this).scrollTop() > 1000) {
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
  $("a").on('click', function(event) {
    if (this.hash !== "") {
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
function open_bouquet_settings_btn() {
	animatedcollapse.show('save_bouquets_btn')
}

// open info broadcast_list_desc
function broadcast_list_desc(id) {
	var this_id = id.replace(/broadcast_/g, "");
	animatedcollapse.addDiv('broadcast_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('broadcast_btn_'+this_id);
}

// display broadcast list main
function broadcast_main(id) {
	$("#broadcast_main_"+id+"").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	//if (id !== 'now_today'){ $("#browse_time_panel").fadeOut("slow"); } else { $("#browse_time_panel").fadeIn("slow"); }
	$.post("functions/broadcast_list_main.php",
	{
	time: id
	},
	function(data){
	$('html, body').animate({ scrollTop: ($(broadcast_list).offset().top)}, 'slow');
	// write data in container
	$("#broadcast_main_"+id+"").html(data);
	}
	);
};

// broadcast list browse time
function broadcast_show_time(id) {
	var time_format = $("#time_format").val();
	var hh = $("#broadcast_hh").val();
	var mm = $("#broadcast_mm").val();
	
	if (time_format == 1) {
	if(isFinite(String(hh)) == false || hh == '' || hh > 23){ var hh = "00"; $("#broadcast_hh").addClass("error-input"); return; } else { $("#broadcast_hh").removeClass("error-input"); };
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#broadcast_mm").addClass("error-input"); return; } else { $("#broadcast_mm").removeClass("error-input"); };
	var am_pm = '0';
	}
	if (time_format == 2) {
	if(isFinite(String(hh)) == false || hh == '' || hh > 12 || hh < 1){ var hh = "12"; $("#broadcast_hh").addClass("error-input"); return; } else { $("#broadcast_hh").removeClass("error-input"); };
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#broadcast_mm").addClass("error-input"); return; } else { $("#broadcast_mm").removeClass("error-input"); };
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
	// write data in container
	$("#broadcast_browse_time").html(data);
	}
	);
};

// broadcast list hover
$(document).ready(function(){
    $("#broadcast_main*").hover(function(){
        $(this).css("background-color", "#FAFAFA");
		//this.style.cursor = 'pointer';
        }, function(){
        $(this).css("background-color", "white");
    });
});

// broadcast banner link
$(document).ready(function() {
    $('#broadcast_banner').click(function(e) {  
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
	if ($(this).scrollTop() > 2500) {
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

// scroll button size
if (document.querySelector('html').clientWidth < 400)
{
	var channelbrowser_btn_size = '2';
	var primetime_btn_size = '2';
	var broadcast_btn_size = '2';
	var scrolltop_btn_size = '2';
	
	} else { 
	
	var channelbrowser_btn_size = '3';
	var primetime_btn_size = '3';
	var broadcast_btn_size = '3';
	var scrolltop_btn_size = '3';
}

// navbar_header
if (document.querySelector('html').clientWidth < 830){
	var navbar_header_dashboard = '';
	var navbar_header_search = '';
	var navbar_header_timer = '';
	var navbar_header_crawl_separate = '';
	var navbar_header_settings = '';
	var navbar_header_channel_list = '';
	var navbar_header_bouquet_list = '';
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
	var navbar_header_records = '<li class="nav-header"><div id="nav-header"><i class="glyphicon glyphicon-record fa-3-5x"></i></div></li>';
	var navbar_header_teletext = '<li class="nav-header"><div id="nav-header"><i class="fa fa-globe fa-3-5x"></i></div></li>';
	var navbar_header_all_services = '<li class="nav-header"><div id="nav-header"><i class="fa fa-list fa-3-5x"></i></div></li>';
	var navbar_header_setup = '<li class="nav-header"><div id="nav-header"><i class="fa fa-wrench fa-3-5x"></i></div></li>';
	var navbar_header_about = '<li class="nav-header"><div id="nav-header"><i class="glyphicon glyphicon-question-sign fa-3-5x"></i></div></li>';
}
//
	document.addEventListener('DOMContentLoaded', checkWidth);
	document.addEventListener('resize', checkWidth);
	
function checkWidth() {
	if (document.querySelector('html').clientWidth > 1200) {
	// statusbar
	$(window).load(function() {
	$.post("functions/statusbar.php",
function(data){		
	// write data in container
	$("#statusbar_cnt").html(data);
	animatedcollapse.addDiv('statusbar_cnt_outter', 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.show('statusbar_cnt_outter');
	});
});
// reload
	var status_bar = "functions/statusbar.php";
	var status_bar_load = 60;

	$(document).ready(function() {
	setInterval(function() {
	$('#statusbar_cnt').load(status_bar + '?sb=' + (new Date().getTime()));
	}, (status_bar_load*1000));
	});
	}
}

// zap request broadcast list main
function broadcast_zap(id,name) {
	
	var this_id = id.replace(/broadcast_zap_btn_/g, "");
	var res = this_id.substr(3);
	
	document.getElementById("broadcast_status_zap_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("broadcast_status_zap_"+this_id+"").innerHTML = "";
		
	document.getElementById("broadcast_zap_btn_"+this_id+"").value = "CHANNEL ZAP OK";
		
	this.close();
	};
	} else {
    document.getElementById("broadcast_status_zap_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// send timer instant broadcast list main
function broadcast_timer(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/broadcast_timer_btn_/g, "");
	var res = this_id.substr(3);
	var record_location = document.getElementById("rec_location_broadcast_"+this_id+"").value;
	
	document.getElementById("broadcast_status_timer_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/send_timer_instant.php?hash="+res+"&record_location="+record_location+"");
    source.onmessage = function(event) {
	
	document.getElementById("broadcast_status_timer_"+this_id+"").innerHTML = event.data;
		
	this.close();
	};
	} else {
	document.getElementById("broadcast_status_timer_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// zap request searchlist
function searchlist_zap(id,name) {
	
	var this_id = id.replace(/searchlist_zap_btn_/g, "");
	
	document.getElementById("searchlist_status_zap_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("searchlist_status_zap_"+this_id+"").innerHTML = "";
	
	document.getElementById("searchlist_zap_btn_"+this_id+"").value = "CHANNEL ZAP OK";
	
	this.close();
	};
	} else {
	document.getElementById("searchlist_status_zap_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// send timer searchlist
function searchlist_timer(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/searchlist_timer_btn_/g, "");
	
	var record_location = document.getElementById("searchlist_record_location_"+this_id+"").value;
	
	document.getElementById("searchlist_status_timer_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/send_timer_instant.php?hash="+this_id+"&record_location="+record_location+"");
    source.onmessage = function(event) {
		
	document.getElementById("searchlist_status_timer_"+this_id+"").innerHTML = event.data;
		
	this.close();
	};
	} else {
	document.getElementById("searchlist_status_timer_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// zap request separate channel crawler
function channel_crawler_zap(id,name) {
	
	var this_id = id.replace(/channel_crawler_zap_/g, "");
	
	document.getElementById("channel_crawler_status_zap_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("channel_crawler_status_zap_"+this_id+"").innerHTML = "";
	
	this.close();
	};
	} else {
	document.getElementById("channel_crawler_status_zap_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// display all channels
function show_all(){
	$("#crawl_separate_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> EPG data is loading, this could take some time..");
	$.post("functions/channel_crawler_separate_inc.php?channel=all",
	function(data){
	// write data in container
	$("#crawl_separate_list").html(data);
	});
}
// show channel panel
function show_panel(){
	animatedcollapse.toggle('single_channel_panel');
}
// display single channel
function show_single_data(){
	var channel = document.getElementById("channel_id").value;
	$("#crawl_separate_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("functions/channel_crawler_separate_inc.php?channel="+channel+"",
	function(data){
	// write data in container
	$("#crawl_separate_list").html(data);
	});
}

// primetime banner link
$(document).ready(function() {
    $('#primetime_banner').click(function(e) {
	primetime_main('primetime_today');
	animatedcollapse.show('primetime_main_today');
    $('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow');
    });
});

// scroll top button primetime
$(document).ready(function(){
$(window).scroll(function(){
	if ($(this).scrollTop() > 4000) {
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
function primetime_list_desc(id) {
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
function primetime_main(id) {
	
	var this_id = id.replace(/primetime_/g, "");
	$("#primetime_main_"+this_id+"").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("functions/primetime_list_main.php",
	{
	action: 'show',
	time: this_id
	},
function(data){
	$('html, body').animate({ scrollTop: ($(primetime_list).offset().top)}, 'slow');
	// write data in container
	$("#primetime_main_"+this_id+"").html(data);
	}
	);
};

// zap request primetime list
function primetime_zap(id,name) {
	
	var this_id = id.replace(/primetime_zap_btn_/g, "");
	
	document.getElementById("primetime_status_zap_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("primetime_status_zap_"+this_id+"").innerHTML = "";
	
	document.getElementById("primetime_zap_btn_"+this_id+"").value = "CHANNEL ZAP OK";
	
	this.close();
	};
	} else {
	document.getElementById("primetime_status_zap_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// send timer primetime list main / search
function primetime_timer(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/primetime_timer_btn_/g, "");
	var record_location = document.getElementById("rec_location_primetime_"+this_id+"").value;
	
	document.getElementById("primetime_status_timer_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/send_timer_instant.php?hash="+this_id+"&record_location="+record_location+"");
    source.onmessage = function(event) {
		
	document.getElementById("primetime_status_timer_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("primetime_status_timer_"+this_id+"").value = "Error";
	}
}

// set primetime
function set_primetime(){
	
	$("#set_status").fadeIn();
	
	var time_format = $("#time_format").val();
	var hh = $("#primetime_hh").val();
	var mm = $("#primetime_mm").val();
	
	if (time_format == 1)
	{
	if(isFinite(String(hh)) == false || hh == '' || hh > 23){ var hh = "12"; $("#primetime_hh").val("12"); };
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#primetime_mm").val("00"); };
	var am_pm = '0';
	}
	
	if (time_format == 2)
	{
	if(isFinite(String(hh)) == false || hh == '' || hh > 12){ var hh = "12"; $("#primetime_hh").addClass("error-input"); } else { $("#primetime_hh").removeClass("error-input"); };
	if(isFinite(String(mm)) == false || mm == '' || mm > 59){ var mm = "00"; $("#primetime_mm").addClass("error-input"); } else { $("#primetime_mm").removeClass("error-input"); };
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
	// write data in container
	$("#set_status").html(data);
	
	$("#set_status").fadeOut(4000);
	
	}
	);
}

// display channelbrowser list main
function channelbrowser_main(id) {
	
	var channel_id = document.getElementById("channel_id").value;
	$("#channelbrowser_main_"+id+"").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	$.post("functions/channelbrowser_list_main.php",
	{
	time: id,
	channel: channel_id
	},
function(data){
	$('html, body').animate({ scrollTop: ($(channelbrowser_list).offset().top)}, 'slow');
	// write data in container
	$("#channelbrowser_main_"+id+"").html(data);
	}
	);
};

// open info channelbrowser_list_desc
function channelbrowser_list_desc(id) {
	
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
$(document).ready(function() {
    $('#channelbrowser_banner').click(function(e) {  
    $('html, body').animate({ scrollTop: ($(channelbrowser_list).offset().top)}, 'slow');
    });
});

// scroll top button channelbrowser
$(document).ready(function(){
$(window).scroll(function(){
	if ($(this).scrollTop() > 5500) {
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

// zap request channelbrowser list
function channelbrowser_zap(id,name) {
	
	var this_id = id.replace(/channelbrowser_zap_btn_/g, "");
	var res = this_id.substr(3);
	
	document.getElementById("channelbrowser_status_zap_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("channelbrowser_status_zap_"+this_id+"").innerHTML = "";
	document.getElementById("channelbrowser_zap_btn_"+this_id+"").value = "CHANNEL ZAP OK";
	
	this.close();
	};
	} else {
	document.getElementById("channelbrowser_status_zap_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// send timer channelbrowser list
function channelbrowser_timer(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/channelbrowser_timer_btn_/g, "");
	var res = this_id.substr(3);
	var record_location = document.getElementById("rec_location_channelbrowser_"+this_id+"").value;
	
	document.getElementById("channelbrowser_status_timer_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
    var source = new EventSource("functions/send_timer_instant.php?hash="+res+"&record_location="+record_location+"");
    source.onmessage = function(event) {
		
	document.getElementById("channelbrowser_status_timer_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("channelbrowser_status_timer_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// open timerlist
function timerlist_desc(id) {
	var this_id = id.replace(/timer_/g, "");
	animatedcollapse.addDiv('timerlist_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('timerlist_btn_'+this_id);
}

// timerlist delete timer
function timerlist_delete_timer(id) {
	
	var this_id = id.replace(/delete_timer_btn_/g, "");
	
	document.getElementById("timerlist_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	if (document.getElementById("timerlist_delete_db_"+this_id+"").checked == true) { var delete_from_db = '1'; }
	if (document.getElementById("timerlist_delete_db_"+this_id+"").checked == false) { var delete_from_db = '0'; }
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/timer_list_inc.php?action=delete&timer_id="+this_id+"&delete_from_db="+delete_from_db+"");
    source.onmessage = function(event) {
	
	document.getElementById("timerlist_status_"+this_id+"").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Timer deleted";

	$("#tl_glyphicon_status_"+this_id+"").attr({style:"color:#D9534F", title:"not sent"});
	
	if (event.data == "deleted_db")
	{ 
	animatedcollapse.addDiv('timerlist_div_outer_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	
function hide_timerlist_div_outer() { animatedcollapse.toggle('timerlist_div_outer_'+this_id);
	}
	window.setTimeout(hide_timerlist_div_outer, 1000);
	}
		
	this.close();
	};
	} else {
	document.getElementById("timerlist_status_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// timerlist send timer
function timerlist_send_timer(id,name) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/timerlist_send_timer_btn_/g, "");
	var record_location = document.getElementById("timerlist_rec_location_"+this_id+"").innerHTML;
	
	document.getElementById("timerlist_status_"+name+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/send_timer_instant.php?location=timerlist&hash="+this_id+"&record_location="+record_location+"");
    
	source.onmessage = function(event) {
		
	document.getElementById("timerlist_status_"+name+"").innerHTML = event.data;
	
	$("#tl_glyphicon_status_"+name+"").attr({style:"color:#5CB85C", title:"sent"});

	this.close();
	};
	} else {
	document.getElementById("timerlist_status_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// timerlist hide timer
function timerlist_hide_timer(id) {
	
	var this_id = id.replace(/timerlist_hide_timer_btn_/g, "");
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/timer_list_inc.php?action=hide&timer_id="+this_id+"");
    source.onmessage = function(event) {
	
	document.getElementById("timerlist_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
function hide_timerlist_div_outer() { 
	
	animatedcollapse.addDiv('timerlist_div_outer_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init();
	animatedcollapse.toggle('timerlist_div_outer_'+this_id);
	}
	window.setTimeout(hide_timerlist_div_outer, 1000);
		
	this.close();
	};
	} else {
	document.getElementById("timerlist_status_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// tickerlist send timer
function tickerlist_send_timer(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/tickerlist_send_timer_btn_/g, "");

	document.getElementById("tickerlist_send_timer_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/send_timer_instant.php?hash="+this_id+"");
    
	source.onmessage = function(event) {
		
	document.getElementById("tickerlist_send_timer_status_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
    alert("Sorry, your browser does not support server-sent events...");
	}
}

// reload timerlist
function reload_timerlist(){
	$.post("functions/timer_list_inc.php",
	function(data){
	$("#selected_box_sum").html("");
	$("#unhide").addClass("hidden");
	$("#show_unhide").attr("onclick", "timerlist_panel(this.id)");
	$("#show_unhide").attr({ title:"show"});
	$("#timerlist_inc").html(data);
});
}

// timerlist panel
function select_timer_checkbox(){
	if (select_all.checked == true){ $("[id^=box]").prop("checked", true); }
	if (select_all.checked == false){ $("[id^=box]").prop("checked", false); }
	count_selected();
}
//
function count_selected(){
	var summary = document.querySelectorAll('input[id^=box]:checked').length;
	$("#selected_box_sum").html("(" + summary + ")");
}
//
function timerlist_panel(id){
	
	$("#panel_action_status").html("");
	$("#panel_action_status").fadeIn();
	
	var checked = []
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	checked.push(parseInt($(this).val()));
	});
	
	if (id == 'delete'){
	$("#del_buttons").toggle();
	return;
	}
	if (id == 'send' || id == 'delete_db' || id == 'delete_rec' || id == 'delete_both' || id == 'hide' || id == 'unhide'){
	if (checked == 0){ return; }
	}
	
	if (id == 'show_unhide'){
	//$("[id^=timerlist_div_outer_]").removeClass("hidden");
	$("#selected_box_sum").html("");
	
	$(function(){
	$.post("functions/timer_list_inc.php",
	{
	action: 'unhide'
	},
function(data){
	$("#unhide").removeClass("hidden");
	$("#select_all").prop("checked", false);
	$("#show_unhide").attr("onclick", "reload_timerlist()");
	$("#show_unhide").attr({ title:"hide"});
	$("#timerlist_inc").html(data);
	});
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
	// write data in container
	$("#panel_action_status").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	
	$("#selected_box_sum").html("");
	
	if (data == 'send_done'){
	$("#panel_action_status").fadeOut(4000);
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	$("#tl_glyphicon_status_"+$(this).val()+"").attr({style:"color:#5CB85C", title:"sent"});
	});
	}
	
	if (data == 'delete_rec_done'){
	$("#panel_action_status").fadeOut(4000);
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	$("#tl_glyphicon_status_"+$(this).val()+"").attr({style:"color:#D9534F", title:"not sent"});
	});
	
	}
	
	if (data == 'unhide_done'){
	$("#panel_action_status").fadeOut(4000);
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	$('#timerlist_div_outer_'+$(this).val()).removeClass("opac_70");
	});
	}
	
function hide_timer_div(){
	if (data == 'delete_db_done' || data == 'delete_both_done' || data == 'hide_done'){
	$("input[name='timerlist_checkbox[]']:checked").each(function ()
	{
	animatedcollapse.addDiv('timerlist_div_outer_'+$(this).val(), 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('timerlist_div_outer_'+$(this).val());
	$("#panel_action_status").fadeOut(1000);
	//$("#panel_action_status").val("");
	});

function unselect_boxes(){
	$("[id^=box]").prop("checked", false);
	}
	window.setTimeout(unselect_boxes, 1500);
	$("#select_all").prop("checked", false);
	}
	}
	window.setTimeout(hide_timer_div, 1000);
	});
}

// display record list
function browse_records() {
	
	var id = document.getElementById("rec_location").value;
	
	$("#record_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">");
	
	$.post("functions/record_list_inc.php",
	{
	record_location: id
	},
function(data){
	// write data in container
	$("#record_list").html(data);
	}
	);
};

// open info record_list
function record_list_desc(id) {
	var this_id = id.replace(/record_/g, "");
	animatedcollapse.addDiv('record_btn_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('record_btn_'+this_id);
}

function reload_rec_location() {

if(typeof(EventSource) !== "undefined") {
	
	alert("Done..");
	
    var source = new EventSource("functions/save_rec_locations.php");
    source.onmessage = function(event) {
		
function refresh_page () { 
	<!--
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = 'records.php");';
	if (document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	window.setTimeout(refresh_page, 1000);
	
	this.close();
	};
	} else {
	document.getElementById("record_list").value = "Sorry, your browser does not support server-sent events...";
	}
}

// create m3u
function create_m3u(id){
	
	if(typeof(EventSource) !== "undefined") {
		
	document.getElementById("m3u_"+id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var record_id = document.getElementById("record_id_"+id+"").innerHTML;
    var source = new EventSource("functions/create_m3u.php?record_file="+id+"&record_id="+record_id+"");
    
	source.onmessage = function(event) {
	
	if (event.data == 'ok') {
	document.getElementById("m3u_"+id+"").innerHTML = "<a href=\"tmp/stream-"+record_id+".m3u\">Download playlist file</a>";
	this.close();
	}
	
	this.close();
	};
	} else {
    document.getElementById("m3u_"+id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// delete record
function delete_record(id){
if (confirm('Are you sure?')) {
	
	var record_no = document.getElementById("record_no_"+id+"").innerHTML;
	
	document.getElementById("del_status_"+record_no+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	$.post("functions/record_list_inc.php?action=delete",
	{
	del_id: id
	},
	function(data){
	animatedcollapse.addDiv('record_entry_'+record_no, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.hide('record_entry_'+record_no);
	}
	);
	} else {
    return;
}
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
function saved_search_list_edit(id) {
	
	var this_id = id.replace(/saved_search_list_/g, "");
	animatedcollapse.addDiv('saved_search_list_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	animatedcollapse.toggle('saved_search_list_div_'+this_id);
}

// edit saved search list
function saved_search_list_save(id) {
	
if(typeof(EventSource) !== "undefined") {
	var this_id = id.replace(/saved_search_list_save_btn_/g, "");
	var searchterm = encodeURIComponent(document.getElementById("searchterm_"+this_id+"").value);
	var searcharea = document.getElementById("searcharea_"+this_id+"").value;
	var exclude_term = encodeURIComponent(document.getElementById("exclude_term_"+this_id+"").value);
	var exclude_area = document.getElementById("exclude_area_"+this_id+"").value;
	var rec_replay = document.getElementById("rec_replay_"+this_id+"").value;
	var channel = document.getElementById("channel_dropdown_saved_search_list_"+this_id+"").value;
	var record_location = document.getElementById("rec_dropdown_saved_search_list_"+this_id+"").value;
	var active = document.getElementById("active_"+this_id+"").value;
	
	document.getElementById("saved_search_list_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/search_list_edit.php?id="+this_id+"&searchterm="+searchterm+"&searcharea="+searcharea+"&exclude_term="+exclude_term+"&exclude_area="+exclude_area+"&rec_replay="+rec_replay+"&channel="+channel+"&record_location="+record_location+"&active="+active+"&action=save");
    
	source.onmessage = function(event) {
		
	document.getElementById("saved_search_list_status_"+this_id+"").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>";
	
	this.close();
	};
	} else {
	document.getElementById("saved_search_list_status_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// saved search list delete
function saved_search_list_delete(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/saved_search_list_delete_btn_/g, "");
	
	document.getElementById("saved_search_list_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/search_list_edit.php?id="+this_id+"&action=delete");
    
	source.onmessage = function(event) {
		
	document.getElementById("saved_search_list_status_"+this_id+"").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Search deleted";
	
	animatedcollapse.addDiv('search_list_div_'+this_id, 'fade=1,height=auto');
	animatedcollapse.init()
	
function hide_search_list_div() { 
	animatedcollapse.toggle('search_list_div_'+this_id);
	}
	window.setTimeout(hide_search_list_div, 1000);
	
	this.close();
	};
	} else {
	document.getElementById("saved_search_list_status_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

<!--
function crawl_complete() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("crawl_complete_btn").disabled = true;
	
    var source = new EventSource("functions/start_channel_crawler_complete.php");
    source.onmessage = function(event) {
	document.getElementById("crawl_complete_status").innerHTML = event.data;

	if (event.data == 'Channel EPG crawling - done!') {
	
function hide_status () { 
	animatedcollapse.hide('crawl_complete_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status () { 
	document.getElementById("crawl_complete_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_crawl_complete () { 
	animatedcollapse.hide('div_crawl_complete');
	}
	window.setTimeout(hide_div_crawl_complete, 3000);
	
function reset_crawl_complete_btn () { 
	document.getElementById("crawl_complete_btn").disabled = false;
	}
	window.setTimeout(reset_crawl_complete_btn, 3500);
	
	this.close();	
	}
    };
	} else {
    document.getElementById("crawl_complete_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

// crawl channel separate
function channel_crawler(id) {
	
if(typeof(EventSource) !== "undefined") {
	var this_id = id.replace(/channel_crawler_/g, "");
	
	document.getElementById("channel_crawler_"+this_id+"").disabled = true;
	document.getElementById("channel_crawler_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
    var source = new EventSource("functions/channel_crawler_separate.php?channel_hash="+this_id+"");
	source.onmessage = function(event) {

	if (event.data == 'channel crawl - done!') {
		
	document.getElementById("channel_crawler_status_"+this_id+"").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>";
		
	document.getElementById("channel_crawler_"+this_id+"").disabled = false;
	
	this.close();	
	}
    };
	} else {
    document.getElementById("channel_crawler_"+this_id+"").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

<!--
function crawl_channel_id() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("crawl_channel_id_btn").disabled = true;
	
    var source = new EventSource("functions/channel_id_crawler.php");
    source.onmessage = function(event) {
	document.getElementById("crawl_channel_id_status").innerHTML = event.data;
		
	<!--if error-->
	if (event.data == 'error') {
	document.getElementById("crawl_channel_id_status").innerHTML = "There was no bouquet selected to crawl. Please check <a href=\"bouquet_list.php\">bouquet list</a>.";
	this.close(); }
	<!---->
	
	if (event.data == 'Channel ID crawling - done!') {
	
function hide_status () { 
	animatedcollapse.hide('crawl_channel_id_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status () { 
	document.getElementById("crawl_channel_id_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_crawl_channel_id () { 
	animatedcollapse.hide('div_crawl_channel_id');
	}
	window.setTimeout(hide_div_crawl_channel_id, 3000);

function reset_crawl_channel_id_btn () { 
	document.getElementById("crawl_channel_id_btn").disabled = false;
	}
	window.setTimeout(reset_crawl_channel_id_btn, 3500);
	
	this.close();
	}
	};
	} else {
	document.getElementById("crawl_channel_id_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

//
function crawl_saved_search() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("crawl_search_btn").disabled = true;

    var source = new EventSource("functions/save_timer_in_db.php");
    source.onmessage = function(event) {
	
	document.getElementById("crawl_search_status").innerHTML = event.data;

	if (event.data == 'Timer from saved search, written in database!') {
	
function hide_status () { 
	animatedcollapse.hide('crawl_search_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status () { 
	document.getElementById("crawl_search_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_crawl_savedsearch () { 
	animatedcollapse.hide('div_crawl_search');
	}
	window.setTimeout(hide_div_crawl_savedsearch, 3000);
	
	function reset_crawl_search_btn () { 
	document.getElementById("crawl_search_btn").disabled = false;
	}
	window.setTimeout(reset_crawl_search_btn, 3500);
	
	this.close();
	}
	};
	} else {
	document.getElementById("crawl_search_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

//
function send_timer() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("send_timer_btn").disabled = true;
	
    var source = new EventSource("functions/send_timer_to_box.php");
    source.onmessage = function(event) {
	
	document.getElementById("send_timer_status").innerHTML = event.data;
	<!---->
	if (event.data == 'Timer was sent from database to Receiver!') {
	
function hide_status () { 
	animatedcollapse.hide('send_timer_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status () { 
	document.getElementById("send_timer_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_send_timer () { 
	animatedcollapse.hide('div_send_timer');
	}
	window.setTimeout(hide_div_send_timer, 3000);
	
function reset_send_timer_btn () { 
	document.getElementById("send_timer_btn").disabled = false;
	}
	window.setTimeout(reset_send_timer_btn, 3500);
	
	this.close();
	}
	};
	} else {
	document.getElementById("send_timer_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

//
function start_channelzapper() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("start_channelzapper_btn").disabled = true;
	
    var source = new EventSource("functions/channelzapper.php?manual=yes");
    source.onmessage = function(event) {
	
	document.getElementById("channelzapper_status").innerHTML = event.data;

	if (event.data == 'all channels zapped - done!') {
	
function hide_status () { 
	animatedcollapse.hide('channelzapper_status');
	}
	window.setTimeout(hide_status, 2000);
	
function reset_status () { 
	document.getElementById("channelzapper_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	}
	window.setTimeout(reset_status, 2500);
	
function hide_div_start_channelzapper () { 
	animatedcollapse.hide('div_start_channelzapper');
	}
	window.setTimeout(hide_div_start_channelzapper, 3000);
	
function reset_start_channelzapper_btn () { 
	document.getElementById("start_channelzapper_btn").disabled = false;
	}
	window.setTimeout(reset_start_channelzapper_btn, 3500);
	
	this.close();	
	}
    };
	} else {
    document.getElementById("channelzapper_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

//
function save_box_settings() {
	var box_ip = encodeURIComponent(document.getElementById("box_ip").value);
	var box_user = encodeURIComponent(document.getElementById("box_user").value);
	var box_password = encodeURIComponent(document.getElementById("box_password").value);
	
	document.getElementById("save_box_settings_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	animatedcollapse.hide('save_box_info');

if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/save_box_settings.php?box_ip="+box_ip+"&box_user="+box_user+"&box_password="+box_password);
    source.onmessage = function(event) {

	<!---if error--->
	if (event.data == 'data missed') {		
	document.getElementById("save_box_settings_status").innerHTML = "Data missed!";
	this.close();
	}
	
	if (event.data == 'settings ok connection ok') {
	document.getElementById("save_box_settings_status").innerHTML = "Settings saved. Data connection to Receiver ok.";
	animatedcollapse.show('save_box_info');
	this.close();
	}
	
	if (event.data == 'settings ok connection error') {
	document.getElementById("save_box_settings_status").innerHTML = "Settings saved, but data connection to Receiver failed. Check settings or <a href=\"help/help.html\">visit help!</a>";
	this.close();
	}
	};
	} else {
	document.getElementById("save_box_settings_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

//
function save_settings() {
	
	var display_time_format = document.getElementById("display_time_format").value;
	if (document.getElementById("activate_cron").checked == true) { var activate_cron = '1'; }
	if (document.getElementById("activate_cron").checked == false) { var activate_cron = '0'; }
	
	var epg_entries_per_channel = encodeURIComponent(document.getElementById("epg_entries_per_channel").value);
	var channel_entries = encodeURIComponent(document.getElementById("channel_entries").value);
	var time_format = document.getElementById("time_format").value;
	
	var dur_down_broadcast = document.getElementById("dur_down_broadcast").value;
	var dur_up_broadcast = document.getElementById("dur_up_broadcast").value;
	
	var dur_down_primetime = document.getElementById("dur_down_primetime").value;
	var dur_up_primetime = document.getElementById("dur_up_primetime").value;
	
	if (document.getElementById("epg_crawler").checked == true) { var epg_crawler = '1'; }
	if (document.getElementById("epg_crawler").checked == false) { var epg_crawler = '0'; }
	
	var crawler_hour = document.getElementById("crawler_hour").value;
	var crawler_minute = document.getElementById("crawler_minute").value;
	
	if (document.getElementById("search_crawler").checked == true) { var search_crawler = '1'; }
	if (document.getElementById("search_crawler").checked == false) { var search_crawler = '0'; }
	
	if (document.getElementById("display_old_epg").checked == true) { var display_old_epg = '1'; }
	if (document.getElementById("display_old_epg").checked == false) { var display_old_epg = '0'; }
	
	if (document.getElementById("streaming_symbol").checked == true) { var streaming_symbol = '1'; }
	if (document.getElementById("streaming_symbol").checked == false) { var streaming_symbol = '0'; }
	
	if (document.getElementById("imdb_symbol").checked == true) { var imdb_symbol = '1'; }
	if (document.getElementById("imdb_symbol").checked == false) { var imdb_symbol = '0'; }
	
	if (document.getElementById("timer_ticker").checked == true) { var timer_ticker = '1'; }
	if (document.getElementById("timer_ticker").checked == false) { var timer_ticker = '0'; }
	
	if (document.getElementById("show_hidden_ticker").checked == true) { var show_hidden_ticker = '1'; }
	if (document.getElementById("show_hidden_ticker").checked == false) { var show_hidden_ticker = '0'; }
	
	var ticker_time = document.getElementById("ticker_time").value;
	
	if (document.getElementById("send_timer").checked == true) { var send_timer = '1'; }
	if (document.getElementById("send_timer").checked == false) { var send_timer = '0'; }
	
	if (document.getElementById("hide_old_timer").checked == true) { var hide_old_timer = '1'; }
	if (document.getElementById("hide_old_timer").checked == false) { var hide_old_timer = '0'; }
	
	if (document.getElementById("delete_old_timer").checked == true) { var delete_old_timer = '1'; }
	if (document.getElementById("delete_old_timer").checked == false) { var delete_old_timer = '0'; }
	
	if (document.getElementById("delete_receiver_timer").checked == true) { var delete_receiver_timer = '1'; }
	if (document.getElementById("delete_receiver_timer").checked == false) { var delete_receiver_timer = '0'; }
	
	if (document.getElementById("dummy_timer").checked == true) { var dummy_timer = '1'; }
	if (document.getElementById("dummy_timer").checked == false) { var dummy_timer = '0'; }
		
	var start_epg_crawler = document.getElementById("start_epg_crawler").value;
	var after_crawl_action = document.getElementById("after_crawl_action").value;
	
	if (document.getElementById("delete_old_epg").checked == true) { var delete_old_epg = '1'; }
	if (document.getElementById("delete_old_epg").checked == false) { var delete_old_epg = '0'; }
	
	var url_format = document.getElementById("url_format").value;
	
	var del_time = document.getElementById("del_time").value;
	var extra_rec_time = document.getElementById("extra_rec_time").value;
	
	if (document.getElementById("reload_progressbar").checked == true) { var reload_progressbar = '1'; }
	if (document.getElementById("reload_progressbar").checked == false) { var reload_progressbar = '0'; }
	
	if (document.getElementById("mark_searchterm").checked == true) { var mark_searchterm = '1'; }
	if (document.getElementById("mark_searchterm").checked == false) { var mark_searchterm = '0'; }
	
	if (document.getElementById("cz_activate").checked == true) { var cz_activate = '1'; }
	if (document.getElementById("cz_activate").checked == false) { var cz_activate = '0'; }
	
	var cz_wait_time = document.getElementById("cz_wait_time").value;
	var cz_hour = document.getElementById("cz_hour").value;
	var cz_minute = document.getElementById("cz_minute").value;
	var cz_repeat = document.getElementById("cz_repeat").value;	
	if (display_time_format == '2'){ var cz_am_pm = document.getElementById("cz_am_pm").value; } else { var cz_am_pm = '0'; }

	var cz_start_channel = document.getElementById("channel_id").value;
	
	document.getElementById("save_settings_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";	
	
	$.post("functions/save_settings.php",
	{
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
	dummy_timer: dummy_timer,
	after_crawl_action: after_crawl_action,
	delete_old_epg: delete_old_epg,
	url_format: url_format,
	del_time: del_time,
	reload_progressbar: reload_progressbar,
	extra_rec_time: extra_rec_time,
	mark_searchterm: mark_searchterm,
	cz_activate: cz_activate,
	cz_wait_time: cz_wait_time,
	cz_hour: cz_hour,
	cz_minute: cz_minute,
	cz_repeat: cz_repeat,
	cz_am_pm: cz_am_pm,
	cz_start_channel: cz_start_channel
	},
	function(data){
	<!---if error--->
	if (data == 'data missed') {		
	document.getElementById("save_settings_status").innerHTML = "Data missed!";
	}	
	if (data == 'ok') {
	document.getElementById("save_settings_status").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i> Settings saved";
	}
	});
}

// copy record locations from receiver
function save_rec_locations() {
	
	document.getElementById("save_box_info_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/save_rec_locations.php");
    source.onmessage = function(event) {
	document.getElementById("save_box_info_status").innerHTML = event.data;

	if (event.data == 'ok') {
	
	document.getElementById("save_box_info_status").innerHTML = "Record locations and bouquets has been copied!";
	
function hide_div_save_box_info_status () { 
	animatedcollapse.hide('save_box_info_status');
	}
	window.setTimeout(hide_div_save_box_info_status, 2000);
	
function hide_div_save_box_info () { 
	animatedcollapse.hide('save_box_info');
	}
	window.setTimeout(hide_div_save_box_info, 2000);
		
function hide_save_box_settings_status () { 
	animatedcollapse.hide('save_box_settings_status');
	}
	window.setTimeout(hide_save_box_settings_status, 2000);
	
	save_bouquet_data();
	
	this.close();
	}
	};
	} else {
	document.getElementById("save_rec_locations_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

// copy bouquet urls from receiver
function save_bouquet_data() {
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/bouquet_crawler.php");
    source.onmessage = function(event) {
	
	function refresh_page(){ 
	<!--
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = 'settings.php");';
	if (document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	window.setTimeout(refresh_page, 2500);
	
	this.close();
	
	};
	} else {
	document.getElementById("save_bouquet_data_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

// set channel to crawl
function set_crawl_channel(id) {
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/set_crawl_channel_/g, "");
	
	document.getElementById("edit_channel_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";

	if (document.getElementById(id).checked == true) { var set_crawler = '1'; }
	if (document.getElementById(id).checked == false) { var set_crawler = '0'; }

    var source = new EventSource("functions/channel_list_inc.php?set_crawl="+set_crawler+"&channel_id="+this_id+"");
	
    source.onmessage = function(event) {
    document.getElementById("edit_channel_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("edit_channel_"+this_id+"").innerHTML = "Sorry, your browser does not support server-sent events...";
	}

}

// set channel for channelzapper
function set_zap_channel(id){
	
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/set_zap_channel_/g, "");
	
	document.getElementById("edit_channel_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";

	if (document.getElementById(id).checked == true) { var set_zap = '1'; }
	if (document.getElementById(id).checked == false) { var set_zap = '0'; }

    var source = new EventSource("functions/channel_list_inc.php?set_zap="+set_zap+"&channel_id="+this_id+"");
	
    source.onmessage = function(event) {
    document.getElementById("edit_channel_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("edit_channel_"+this_id+"").innerHTML = "Sorry, your browser does not support server-sent events...";
	}

}

// add channel
function add_single_channel() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("add_single_channel_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var channel_name = document.getElementById("channel_name").value;
	var service_reference = document.getElementById("service_reference").value;

    var source = new EventSource("functions/channel_list_inc.php?action=add&channel_name="+channel_name+"&service_reference="+service_reference+"");
	
    source.onmessage = function(event) {
	
	if (event.data == 'ok') {
	document.getElementById("add_single_channel_status").innerHTML = "<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>";
	this.close(); 
	
	function refresh_page () { 
	<!--
	s1 = 'loca';
	s2 = 'tion.r';
	s3 = 'eplace("';
	s4 = 'channel_list.php");';
	if (document.all || document.getElementById || document.layers)
	eval(s1+s2+s3+s4);
	}
	window.setTimeout(refresh_page, 1000);	
	}
	
	<!--if error-->
	if (event.data == 'error') {
	document.getElementById("add_single_channel_status").innerHTML = "<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>";
	this.close(); }
	<!---->
	
	this.close();
	
	};
	} else {
	document.getElementById("add_single_channel_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}

}

// edit bouquet list
function save_bouquet_settings(id)
	{
if(typeof(EventSource) !== "undefined") {
	
	var this_id = id.replace(/save_bouquet_settings_status_/g, "");
	
	document.getElementById("save_bouquet_settings_status_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";

	if (document.getElementById(id).checked == true) { var crawl_bouquet = '1'; }
	if (document.getElementById(id).checked == false) { var crawl_bouquet = '0'; }

    var source = new EventSource("functions/save_bouquet_settings.php?crawl_bouquet="+crawl_bouquet+"&bouquet_id="+id+"");
	
    source.onmessage = function(event) {
    
	document.getElementById("save_bouquet_settings_status_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("save_bouquet_settings_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

// add custom bouquet
function add_custom_bouquet() {
	
if(typeof(EventSource) !== "undefined") {
	
	document.getElementById("add_custom_bouquet_status").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var custom_bouquet_url = document.getElementById("custom_bouquet_url").value;
	var custom_bouquet_title = document.getElementById("custom_bouquet_title").value;
	
    var source = new EventSource("functions/add_custom_bouquet.php?custom_bouquet_url="+custom_bouquet_url+"&custom_bouquet_title="+custom_bouquet_title);
    source.onmessage = function(event) {
		
	document.getElementById("add_custom_bouquet_status").innerHTML = event.data;
	
	this.close();
	
	};
	} else {
	document.getElementById("add_custom_bouquet_status").innerHTML = "Sorry, your browser does not support server-sent events...";
	}
}

// power control
function power_control(id){
	
if(typeof(EventSource) !== "undefined") {
	
	$("#pc"+id+"").fadeIn();
	document.getElementById("pc"+id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";

    var source = new EventSource("functions/power_control.php?command="+id+"");
    source.onmessage = function(event) {
	
	if (event.data == 'true') {
	document.getElementById("pc"+id+"").innerHTML = "<i class=\"glyphicon glyphicon-arrow-down fa-1x\" style=\"color:#D9534F\"></i>";
	$("#pc"+id+"").fadeOut(4000);
	this.close(); }
	
	if (event.data == 'error') {
	document.getElementById("pc"+id+"").innerHTML = "<i class=\"glyphicon glyphicon-remove fa-1x\" style=\"color:#D9534F\"></i>";
	this.close(); }
	
	if (event.data == 'false') {
	document.getElementById("pc"+id+"").innerHTML = "<i class=\"glyphicon glyphicon-arrow-up fa-1x\" style=\"color:#5CB85C\"></i>";
	$("#pc"+id+"").fadeOut(4000);
	this.close(); }
	
	};
	} else {
	alert("Sorry, your browser does not support server-sent events...");
	}
}

// teletext
function teletext_page() {

	document.getElementById("teletext_img").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var number = document.getElementById("page").value;
	var size = document.getElementById("size").value;
	
	$.post("functions/teletext_inc.php",
	{
	page: number,
	resolution: size
	},
	function(data){
	// write data in container
	$("#teletext_img").html(data);
	});
};

// browse page
function teletext_browse(id) {

	document.getElementById("teletext_img").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var number = document.getElementById("page").value;
	var size = document.getElementById("size").value;
	
	$.post("functions/teletext_inc.php",
	{
	browse: id,
	resolution: size
	},
	function(data){
	// write data in container
	$("#teletext_img").html(data);
	}
	);
};

// control
function teletext_control(id) {
	
	document.getElementById("teletext_img").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
	var size = document.getElementById("size").value;
	
	$.post("functions/teletext_inc.php",
	{
	control: id,
	resolution: size
	},
	function(data){
	// write data in container
	$("#teletext_img").html(data);
	}
	);
};

// add channel from services list
function all_services_add(id,name) {
	
	var this_id = id.replace(/all_services_add_btn_/g, "");
	
	document.getElementById("all_services_status_add_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/services_inc.php?action=add&id="+name+"");
    source.onmessage = function(event) {
		
	document.getElementById("all_services_status_add_"+this_id+"").innerHTML = event.data;
	
	this.close();
	};
	} else {
	document.getElementById("all_services_status_add_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// services zap request
function all_services_zapp(id,name) {
	
	var this_id = id.replace(/all_services_zapp_btn_/g, "");
	
	document.getElementById("all_services_status_zapp_"+this_id+"").innerHTML = "<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\">";
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/send_zapp_request.php?e2servicereference="+name+"");
    source.onmessage = function(event) {

	$("#all_services_status_zapp_"+this_id+"").html("<i class=\"glyphicon glyphicon-ok fa-1x\" style=\"color:#5CB85C\"></i>");
	$("#all_services_status_zapp_"+this_id+"").fadeOut(4000);
	$("#all_services_status_zapp_"+this_id+"").innerHTML("");
	
	this.close();
	};
	} else {
	document.getElementById("all_services_status_zapp_"+this_id+"").value = "Sorry, your browser does not support server-sent events...";
	}
}

// crawl all services
function all_services_crawl() {

	$("#all_services_list").html("<img src=\"images/loading.gif\" width=\"16\" height=\"16\" align=\"absmiddle\"> Copy services from Receiver..");
	
if(typeof(EventSource) !== "undefined") {
	
    var source = new EventSource("functions/services_inc.php?action=crawl");
    source.onmessage = function(event) {
	
	$.post("functions/services_inc.php",
	function(data){
	// write data in container
	$("#all_services_list").html(data);
	}
	);
	
	this.close();
	};
	} else {
	document.getElementById("all_services_list").value = "Sorry, your browser does not support server-sent events...";
	}
}
