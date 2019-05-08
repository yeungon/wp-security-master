/*function to handle the click to extend the timespan*/
function getValue(time){

		let hour = time.getAttribute("data-value");

		jQuery.ajax({
		url 		: object_annotate.url, //object_annotate is the object from wp-security-master register with wp_localize_script || ajaxurl can be used instead		
		type 		: 'POST',
		dataType 	: 'text',
		data 		: {
					action 		: 'getMessageAction', 				
					hourupdate 	: hour,
					nonce_data 	: object_annotate.ajax_nonce,
		},
		
		success : function( response ) {
			
			console.log("Success. Updated with " + hour + " hour(s)");			
						
			var update 		= document.getElementById('ajaxtime');			
			
			if(hour > 1){

				var timeunit = ' hours';

			}else{

				var timeunit = ' hour';

			}

			update.innerText =  hour + timeunit;

			var setting = hour*3600;

			/*Display on the admin bar*/
			let update_clock = document.getElementById('timetolock');								
			update_clock.style.display = 'none';

			/*Hiding the old value when clicking "Extend" */
			let extendbutton = document.getElementById('extendid');
			extendbutton.style.display = 'none';

			/*Hiding the word "second"*/
			let secondwordid = document.getElementById('secondwordid');
			secondwordid.style.display = 'none';
		
		},

		fail : function( response ) {
			 console.log("Fail");
		
		}

	});
					
}

