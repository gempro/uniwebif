
function foo(){
	
	// delay time
	for ($i = 0; $i <= 0; $i++)
 	{
	var x = $i+1;
	var delay_time = 5000*x;
	if(delay_time == 0){ var delay_time = 5000; }
	//
	
	new Noty({
	
   type: 'success',
       layout: 'topRight',
       text: "<strong>Test ok</strong>",
       timeout: delay_time,
       modal: false,
       closeWith: ['button'],
       animation: {
        open: 'animated2 bounceInRight', // Animate.css class names
        close: 'animated bounceOutRight' // Animate.css class names
		},
	    callbacks: {
//		beforeShow: function() {},
//		onShow: function() {},
//		afterShow: function() {},
		onClose: function() { console.log("closed"); },
//		afterClose: function() { test(); },
//		onHover: function() {},
//		onTemplate: function() {
//		this.barDom.innerHTML = '<div class="my-custom-template noty_body">' + this.options.text + '<div>';
//		// Important: .noty_body class is required for setText API method.
//		}
    } // callbacks
	
	}).show();
	
	} // for i delay time
	
	} // function foo
	
//
//function test()
//	{ 
//	var text = document.getElementById("test").value; console.log(text); 
//	}
