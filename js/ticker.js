// JavaScript Document
// banner
$(document).ready(function() {
	alert("bam oida");
	$.post("../ticker/ticker.html",
	{
	//time: id
	},
	function(data){
	// write data in container
	$("#ticker").html(data);
	alert("bum oida");
	});
});