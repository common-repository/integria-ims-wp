//=== JQUERY Functions Setup =================================

	function check_connection_integria(){

		jQuery(document).ready(function($) {

			jQuery('#loading_setup').css({'display':'inline' , 'margin-left' : '20px'});
			jQuery('#false_setup').css('display','none');
			jQuery('#true_setup').css('display','none');

			var api_url = jQuery('#api_url').val();
			var user_id = jQuery('#user_id').val();
			var user_pass = jQuery('#user_pass').val();
			var api_pass = jQuery('#api_pass').val();

			var data = {
				'action' : 'check_connection_integria',
				'api_url' : api_url,
				'user_id' : user_id,
				'user_pass' : user_pass,
				'api_pass' : api_pass
			}

			jQuery.post(ajaxurl, data, function(response) {


				if(response != 0){ 
					jQuery('#false_setup').css('display','none');
					jQuery('#loading_setup').css('display','none');
					jQuery('#true_setup').css({'display':'inline' , 'margin-left' : '20px'});
				}
				else{	
					jQuery('#true_setup').css('display','none');
					jQuery('#loading_setup').css('display','none');
					jQuery('#false_setup').css({'display':'inline' , 'margin-left' : '20px'});
				}


			})

				
		})

	}



	function check_api_version(){

		jQuery(document).ready(function($) {


			jQuery("#check_api_version").html('');
			jQuery('#loading_version').css({'display':'inline' , 'margin-left' : '20px'});
			jQuery('#false_version').css('display','none');
			jQuery('#true_version').css('display','none');

			var api_url = jQuery('#api_url').val();
			//api_url= api_url+'?info=version';

			var data = {
				'action' : 'check_api_version',
				'api_url' : api_url		
			}


			jQuery.post(ajaxurl, data, function(response) {


				if(response != 0){ 
					jQuery("#check_api_version").html(response).css('color', 'black');	
					jQuery('#false_version').css('display','none');
					jQuery('#loading_version').css('display','none');
					jQuery('#true_version').css({'display':'inline' , 'margin-left' : '20px'});
				}
				else{	
					jQuery("#check_api_version").html('Type a valid URL.').css('color', 'red');
					jQuery('#true_version').css('display','none');
					jQuery('#loading_version').css('display','none');
					jQuery('#false_version').css({'display':'inline' , 'margin-left' : '20px'});
				}


			})


				
		})

	}

//=== FIN === JQUERY Functions Setup ============================


//=== JQUERY Functions Leads AND Tickets ========================

	// ===== Button toggle for button create/hide form ==============
	function create_toggle(){

		jQuery(document).ready(function($) {

			jQuery('#create_toggle').toggle();
			jQuery('#create_toggle_tickets').toggle();

			var display = jQuery('#create_toggle').css('display');
			var display2 = jQuery('#create_toggle_tickets').css('display');

			if (display == 'none' || display2 == 'none') {
				jQuery('#create_form_data').val('Create');
				jQuery('#create_form_data_tickets').val('Create Ticket');
			}
			else{
				jQuery('#create_form_data').val('Hide Creation Form');
			    jQuery('#create_form_data_tickets').val('Hide Creation Form');
			}

		})

	}
	// ===== END === Button toggle for button create/hide form =====

//=== END === JQUERY Functions Leads AND Tickets ===============



// ===== JQUERY LEADS Functions ============================== 	

	// ===== DELETE rows from the table form_data ==================
	function delete_form_data(id_input_table){

		jQuery(document).ready(function($) {

			var data = {
				'action': 'delete_row_form_data',
				'id_input_table' : id_input_table,
			}

	
			jQuery.post(ajaxurl, data, function(response) {
				
				if(response){
					jQuery('#id_fila_'+id_input_table).hide();
				}

			})

		})


	}

	// ===== END === DELETE rows from the table form_data ===========


	// ===== LOAD FIELDS from the LEADS FORM =======================
	function load_values(var_id_form) {

		jQuery(document).ready(function($) {

			var data = {
				'variable_id_form': var_id_form
			}

			jQuery.post(ajaxurl, data, function(response) {
				//console.log('llamada ajax' + var_id_form);
				
			})

			var obj = jQuery.parseJSON( jQuery("#hidden_leads").val() );
		
			var select = jQuery('[name="options[foo]"]');
			var select1 = jQuery('#name');		
			var select2 = jQuery('#email');
			var select3 = jQuery('#phone');
			var select4 = jQuery('#company');
			var select5 = jQuery('#know_us');
			var select6 = jQuery('#message-l');					

			if(select.prop) {
				var options1 = select1.prop('options');
				var options2 = select2.prop('options');
				var options3 = select3.prop('options');
				var options4 = select4.prop('options');
				var options5 = select5.prop('options');
				var options6 = select6.prop('options');
			}
			else {
				var options1 = select1.atrr('options');
				var options2 = select2.atrr('options');
				var options3 = select3.atrr('options');
				var options4 = select4.atrr('options');
				var options5 = select5.atrr('options');
				var options6 = select6.atrr('options');
			}

			jQuery('option', select).remove();


			for (var i in obj) {
					
			    if(parseInt(obj[i]) === parseInt(var_id_form)){

			      	options1[options1.length] = new Option(i, i);
			      	options2[options2.length] = new Option(i, i);
			      	options3[options3.length] = new Option(i, i);
			      	options4[options4.length] = new Option(i, i);
			      	options5[options5.length] = new Option(i, i);
			      	options6[options6.length] = new Option(i, i);			

			      }
	     	}
			  
			select1.val(options1);
			select2.val(options2);
			select3.val(options3);
			select4.val(options4);
			select5.val(options5);
			select6.val(options6);
			
		})

	} 

	// ===== END === LOAD FIELDS from the LEADS FORM ===============


	// ===== GET FIELDS from the LEADS FORM ========================


	function get_data_form(){
		

		jQuery(document).ready(function($) {
			var id_form = jQuery('#id_form').val();
			var name_form = jQuery.trim(jQuery('#id_form option:selected').text());

			var name = jQuery('#name').val();
			var email = jQuery('#email').val();
			var phone = jQuery('#phone').val();
			var company = jQuery('#company').val();
			var know_us = jQuery('#know_us').val();
			var message = jQuery('#message-l').val();
			var language = jQuery('#language').val();
			var id_product = jQuery('#id_product').val();
			var tags = jQuery('#tags').val();

			// null : for select option
			//   '' : for input type text and select option when there isn't any form selected
			if( name != '' && email != '' && phone != '' && company != '' && know_us != '' &&  message != '' && 
				language != '' && id_product != '' && 
				name != null && email != null && phone != null && company != null && know_us != null &&  message != null && 
				language != null && id_product != null ){ 


				var valid_languages_values = ['de','en_GB','es','fr','pl','ru','zh_CN'];	

				if( jQuery.inArray(language,valid_languages_values) === -1 ){
					jQuery('#show-message').text('The valid values for Language are: de, en_GB, es, fr, pl, ru, zh_CN.').addClass('error').css('color', 'red');
				}
				else if(!jQuery.isNumeric(id_product)){
					jQuery('#show-message').text('ID Product is not a number.').addClass('error').css('color', 'red');
				}
				else{
					

					var data = {
						'action': 'set_data_form',
						'id_form' : id_form,
						'name_form' : name_form,

						'name' : name,
						'email' : email,
						'phone' : phone,
						'company' : company,
						'know_us' : know_us,
						'message' : message,
						'language' : language,
						'id_product' : id_product,
						'tags' : tags
					}


					jQuery.post(ajaxurl, data, function(response) {
							
						//alert('Got this from the server: ' + response);

						if(response != 1){
							//console.log('duplicate');
							jQuery('#show-message').text('Error. Check if your form already exists in the table.').addClass('error').css('color', 'red');
						}
						else{
							//console.log('correct');
							jQuery('#show-message').text('It has been inserted successfully.').addClass('updated').css('color', 'green');
							location.reload(true);

						}



					})


				}


			}
			else{

				jQuery('#show-message').text('You can not leave any empty fields.').addClass('error').css('color', 'red');

			}


		})


	}

	// ===== END === GET FIELDS from the LEADS FORM ================

	
// ===== END === JQUERY LEADS Functions ====================== 



// ===== JQUERY TICKETS Functions ============================ 

	// ===== DELETE rows from the table form_data_tickets ==============
	function delete_form_data_tickets(id_input_table){

		jQuery(document).ready(function($) {

			var data = {
				'action': 'delete_row_form_data_tickets',
				'id_input_table' : id_input_table,
			}

	
			jQuery.post(ajaxurl, data, function(response) {
					
				if(response){
					jQuery('#id_fila_tickets_'+id_input_table).hide();
				}

			})


		})

	}
	// ===== END === DELETE rows from the table form_data_tickets ======


	// ===== LOAD FIELDS from the TICKETS FORM =========================
	function load_values_tickets(var_id_form) {

		jQuery(document).ready(function($) {

			var data = {
				'variable_id_form': var_id_form
			}

			jQuery.post(ajaxurl, data, function(response) {
				//console.log('llamada ajax' + var_id_form);
				
			})

			var obj = jQuery.parseJSON( jQuery("#hidden_tickets").val() );

			var select = jQuery('[name="options[foo-t]"]');
			var select1 = jQuery('#title');		
			var select2 = jQuery('#id_group');
			var select3 = jQuery('#priority');
			var select4 = jQuery('#description');
			var select5 = jQuery('#status');
			var select6 = jQuery('#id_incident_type');
			
			var select7 = jQuery('#field1');
			var select8 = jQuery('#field2');
			var select9 = jQuery('#field3');
			var select10 = jQuery('#field4');
			var select11 = jQuery('#field5');
			var select12 = jQuery('#field6');

			if(select.prop) {
				var options1 = select1.prop('options');
				var options2 = select2.prop('options');
				var options3 = select3.prop('options');
				var options4 = select4.prop('options');
				var options5 = select5.prop('options');
				var options6 = select6.prop('options');
				
				var options7 = select7.prop('options');
				var options8 = select8.prop('options');
				var options9 = select9.prop('options');
				var options10 = select10.prop('options');
				var options11 = select11.prop('options');
				var options12 = select12.prop('options');


			}
			else {
				var options1 = select1.atrr('options');
				var options2 = select2.atrr('options');
				var options3 = select3.atrr('options');
				var options4 = select4.atrr('options');
				var options5 = select5.atrr('options');
				var options6 = select6.atrr('options');
				
				var options7 = select7.atrr('options');
				var options8 = select8.atrr('options');
				var options9 = select9.atrr('options');
				var options10 = select10.atrr('options');
				var options11 = select11.atrr('options');
				var options12 = select12.atrr('options');
			}

			jQuery('option', select).remove(); 
			

			for (var i in obj) {
						
			    if(parseInt(obj[i]) === parseInt(var_id_form)){
			      	options1[options1.length] = new Option(i, i);
			      	options2[options2.length] = new Option(i, i);
			      	options3[options3.length] = new Option(i, i);
			      	options4[options4.length] = new Option(i, i);
							options5[options5.length] = new Option(i, i);
							options6[options6.length] = new Option(i, i);
							
							options7[options7.length] = new Option(i, i);
							options8[options8.length] = new Option(i, i);
							options9[options9.length] = new Option(i, i);
							options10[options10.length] = new Option(i, i);
							options11[options11.length] = new Option(i, i);
							options12[options12.length] = new Option(i, i);								
			      }
	     	}
			  
			
			select1.val(options1);
			select2.val(options2);
			select3.val(options3);
			select4.val(options4);
			select5.val(options5);
			select6.val(options6);
			
			select7.val(options7);
			select8.val(options8);
			select9.val(options9);
			select10.val(options10);
			select11.val(options11);
			select12.val(options12);

		})

	} 
	//===== END === LOAD FIELDS from the TICKETS FORM ==================


	// ===== GET FIELDS from the TICKETS FORM ==========================
	function get_data_form_tickets(){
		
		jQuery(document).ready(function($) {

			var id_form = jQuery('#id_form_tickets').val();
			var name_form = jQuery.trim(jQuery('#id_form_tickets option:selected').text());
	
			
			// If title-static is full, use it
			if(jQuery('#title-static').val().length > 0 && (jQuery('#title').val() == '' || jQuery('#title').val() == null)){	// vacio lleno
				var title = '-';
				var title_value = jQuery('#title-static').val();
			}
			else if (jQuery('#title-static').val().length <= 0 && (jQuery('#title').val() == '' || jQuery('#title').val() == null)) { //vacio vacio
				var title = '';
				var title_value = '';
			}
			else if (jQuery('#title-static').val().length <= 0 && (jQuery('#title').val() != '' || jQuery('#title').val() != null)) { //lleno vacio
				var title = jQuery('#title').val();
				var title_value = '-';
			}
			else if (jQuery('#title-static').val().length > 0 && (jQuery('#title').val() != '' || jQuery('#title').val() != null)) { //lleno lleno
				var title = '-';
				var title_value = jQuery('#title-static').val();
			}
			else{ //??
				var title = '';
				var title_value = '';
			}



			// If id_group-static is full, use it
			if(jQuery('#id_group-static').val().length > 0 && (jQuery('#id_group').val() == '' || jQuery('#id_group').val() == null)){	// vacio lleno
				var id_group = '-';
				var id_group_value = jQuery('#id_group-static').val();
			}
			else if (jQuery('#id_group-static').val().length <= 0 && (jQuery('#id_group').val() == '' || jQuery('#id_group').val() == null)) { //vacio vacio
				var id_group = '';
				var id_group_value = '';
			}
			else if (jQuery('#id_group-static').val().length <= 0 && (jQuery('#id_group').val() != '' || jQuery('#id_group').val() != null)) { //lleno vacio
				var id_group = jQuery('#id_group').val();
				var id_group_value = '-';
			}
			else if (jQuery('#id_group-static').val().length > 0 && (jQuery('#id_group').val() != '' || jQuery('#id_group').val() != null)) { //lleno lleno
				var id_group = '-';
				var id_group_value = jQuery('#id_group-static').val();
			}
			else{ //??
				var id_group = '';
				var id_group_value = '';
			}



			// If priority-static is full, use it
			if(jQuery('#priority-static').val().length > 0 && (jQuery('#priority').val() == '' || jQuery('#priority').val() == null)){	// vacio lleno
				var priority = '-';
				var priority_value = jQuery('#priority-static').val();
			}
			else if (jQuery('#priority-static').val().length <= 0 && (jQuery('#priority').val() == '' || jQuery('#priority').val() == null)) { //vacio vacio
				var priority = '';
				var priority_value = '';
			}
			else if (jQuery('#priority-static').val().length <= 0 && (jQuery('#priority').val() != '' || jQuery('#priority').val() != null)) { //lleno vacio
				var priority = jQuery('#priority').val();
				var priority_value = '-';
			}
			else if (jQuery('#priority-static').val().length > 0 && (jQuery('#priority').val() != '' || jQuery('#priority').val() != null)) { //lleno lleno
				var priority = '-';
				var priority_value = jQuery('#priority-static').val();
			}
			else{ //??
				var priority = '';
				var priority_value = '';
			}



			// If description-static is full, use it
			if(jQuery('#description-static').val().length > 0 && (jQuery('#description').val() == '' || jQuery('#description').val() == null)){	// vacio lleno
				var description = '-';
				var description_value = jQuery('#description-static').val();
			}
			else if (jQuery('#description-static').val().length <= 0 && (jQuery('#description').val() == '' || jQuery('#description').val() == null)) { //vacio vacio
				var description = '';
				var description_value = '';
			}
			else if (jQuery('#description-static').val().length <= 0 && (jQuery('#description').val() != '' || jQuery('#description').val() != null)) { //lleno vacio
				var description = jQuery('#description').val();
				var description_value = '-';
			}
			else if (jQuery('#description-static').val().length > 0 && (jQuery('#description').val() != '' || jQuery('#description').val() != null)) { //lleno lleno
				var description = '-';
				var description_value = jQuery('#description-static').val();
			}
			else{ //??
				var description = '';
				var description_value = '';
			}


			// If status-static is full, use it
			if(jQuery('#status-static').val().length > 0 && (jQuery('#status').val() == '' || jQuery('#status').val() == null)){	// vacio lleno
				var status = '-';
				var status_value = jQuery('#status-static').val();
			}
			else if (jQuery('#status-static').val().length <= 0 && (jQuery('#status').val() == '' || jQuery('#status').val() == null)) { //vacio vacio
				var status = '';
				var status_value = '';
			}
			else if (jQuery('#status-static').val().length <= 0 && (jQuery('#status').val() != '' || jQuery('#status').val() != null)) { //lleno vacio
				var status = jQuery('#status').val();
				var status_value = '-';
			}
			else if (jQuery('#status-static').val().length > 0 && (jQuery('#status').val() != '' || jQuery('#status').val() != null)) { //lleno lleno
				var status = '-';
				var status_value = jQuery('#status-static').val();
			}
			else{ //??
				var status = '';
				var status_value = '';
			}


			if(jQuery('#id_incident_type-static').val().length > 0 && (jQuery('#id_incident_type').val() == '' || jQuery('#id_incident_type').val() == null)){	// vacio lleno
				var id_incident_type = '-';
				var id_incident_type_value = jQuery('#id_incident_type-static').val();
			}
			else if (jQuery('#id_incident_type-static').val().length <= 0 && (jQuery('#id_incident_type').val() == '' || jQuery('#id_incident_type').val() == null)) { //vacio vacio
				var id_incident_type = '';
				var id_incident_type_value = '';
			}
			else if (jQuery('#id_incident_type-static').val().length <= 0 && (jQuery('#id_incident_type').val() != '' || jQuery('#id_incident_type').val() != null)) { //lleno vacio
				var id_incident_type = jQuery('#id_incident_type').val();
				var id_incident_type_value = '-';
			}
			else if (jQuery('#id_incident_type-static').val().length > 0 && (jQuery('#id_incident_type').val() != '' || jQuery('#id_incident_type').val() != null)) { //lleno lleno
				var id_incident_type = '-';
				var id_incident_type_value = jQuery('#id_incident_type-static').val();
			}
			else{ //??
				var id_incident_type = '';
				var id_incident_type_value = '';
			}


			if(jQuery('#field1-static').val().length > 0 && (jQuery('#field1').val() == '' || jQuery('#field1').val() == null)){	// vacio lleno
				var field1 = '-';
				var field1_value = jQuery('#field1-static').val();
			}
			else if (jQuery('#field1-static').val().length <= 0 && (jQuery('#field1').val() == '' || jQuery('#field1').val() == null)) { //vacio vacio
				var field1 = '';
				var field1_value = '';
			}
			else if (jQuery('#field1-static').val().length <= 0 && (jQuery('#field1').val() != '' || jQuery('#field1').val() != null)) { //lleno vacio
				var field1 = jQuery('#field1').val();
				var field1_value = '-';
			}
			else if (jQuery('#field1-static').val().length > 0 && (jQuery('#field1').val() != '' || jQuery('#field1').val() != null)) { //lleno lleno
				var field1 = '-';
				var field1_value = jQuery('#field1-static').val();
			}
			else{ //??
				var field1 = '';
				var field1_value = '';
			}


			if(jQuery('#field2-static').val().length > 0 && (jQuery('#field2').val() == '' || jQuery('#field2').val() == null)){	// vacio lleno
				var field2 = '-';
				var field2_value = jQuery('#field2-static').val();
			}
			else if (jQuery('#field2-static').val().length <= 0 && (jQuery('#field2').val() == '' || jQuery('#field2').val() == null)) { //vacio vacio
				var field2 = '';
				var field2_value = '';
			}
			else if (jQuery('#field2-static').val().length <= 0 && (jQuery('#field2').val() != '' || jQuery('#field2').val() != null)) { //lleno vacio
				var field2 = jQuery('#field2').val();
				var field2_value = '-';
			}
			else if (jQuery('#field2-static').val().length > 0 && (jQuery('#field2').val() != '' || jQuery('#field2').val() != null)) { //lleno lleno
				var field2 = '-';
				var field2_value = jQuery('#field2-static').val();
			}
			else{ //??
				var field2 = '';
				var field2_value = '';
			}


			if(jQuery('#field3-static').val().length > 0 && (jQuery('#field3').val() == '' || jQuery('#field3').val() == null)){	// vacio lleno
				var field3 = '-';
				var field3_value = jQuery('#field3-static').val();
			}
			else if (jQuery('#field3-static').val().length <= 0 && (jQuery('#field3').val() == '' || jQuery('#field3').val() == null)) { //vacio vacio
				var field3 = '';
				var field3_value = '';
			}
			else if (jQuery('#field3-static').val().length <= 0 && (jQuery('#field3').val() != '' || jQuery('#field3').val() != null)) { //lleno vacio
				var field3 = jQuery('#field3').val();
				var field3_value = '-';
			}
			else if (jQuery('#field3-static').val().length > 0 && (jQuery('#field3').val() != '' || jQuery('#field3').val() != null)) { //lleno lleno
				var field3 = '-';
				var field3_value = jQuery('#field3-static').val();
			}
			else{ //??
				var field3 = '';
				var field3_value = '';
			}


			if(jQuery('#field4-static').val().length > 0 && (jQuery('#field4').val() == '' || jQuery('#field4').val() == null)){	// vacio lleno
				var field4 = '-';
				var field4_value = jQuery('#field4-static').val();
			}
			else if (jQuery('#field4-static').val().length <= 0 && (jQuery('#field4').val() == '' || jQuery('#field4').val() == null)) { //vacio vacio
				var field4 = '';
				var field4_value = '';
			}
			else if (jQuery('#field4-static').val().length <= 0 && (jQuery('#field4').val() != '' || jQuery('#field4').val() != null)) { //lleno vacio
				var field4 = jQuery('#field4').val();
				var field4_value = '-';
			}
			else if (jQuery('#field4-static').val().length > 0 && (jQuery('#field4').val() != '' || jQuery('#field4').val() != null)) { //lleno lleno
				var field4 = '-';
				var field4_value = jQuery('#field4-static').val();
			}
			else{ //??
				var field4 = '';
				var field4_value = '';
			}


			if(jQuery('#field5-static').val().length > 0 && (jQuery('#field5').val() == '' || jQuery('#field5').val() == null)){	// vacio lleno
				var field5 = '-';
				var field5_value = jQuery('#field5-static').val();
			}
			else if (jQuery('#field5-static').val().length <= 0 && (jQuery('#field5').val() == '' || jQuery('#field5').val() == null)) { //vacio vacio
				var field5 = '';
				var field5_value = '';
			}
			else if (jQuery('#field5-static').val().length <= 0 && (jQuery('#field5').val() != '' || jQuery('#field5').val() != null)) { //lleno vacio
				var field5 = jQuery('#field5').val();
				var field5_value = '-';
			}
			else if (jQuery('#field5-static').val().length > 0 && (jQuery('#field5').val() != '' || jQuery('#field5').val() != null)) { //lleno lleno
				var field5 = '-';
				var field5_value = jQuery('#field5-static').val();
			}
			else{ //??
				var field5 = '';
				var field5_value = '';
			}
			
			
			if(jQuery('#field6-static').val().length > 0 && (jQuery('#field6').val() == '' || jQuery('#field6').val() == null)){	// vacio lleno
				var field6 = '-';
				var field6_value = jQuery('#field6-static').val();
			}
			else if (jQuery('#field6-static').val().length <= 0 && (jQuery('#field6').val() == '' || jQuery('#field6').val() == null)) { //vacio vacio
				var field6 = '';
				var field6_value = '';
			}
			else if (jQuery('#field6-static').val().length <= 0 && (jQuery('#field6').val() != '' || jQuery('#field6').val() != null)) { //lleno vacio
				var field6 = jQuery('#field6').val();
				var field6_value = '-';
			}
			else if (jQuery('#field6-static').val().length > 0 && (jQuery('#field6').val() != '' || jQuery('#field6').val() != null)) { //lleno lleno
				var field6 = '-';
				var field6_value = jQuery('#field6-static').val();
			}
			else{ //??
				var field6 = '';
				var field6_value = '';
			}


			if( title != '' && id_group != '' && priority != '' && description != '' && status != '' && id_incident_type != '' 
				&& title_value != '' && id_group_value != '' && priority_value != '' && description_value != '' && status_value != '' && id_incident_type_value != ''
				&& title != null && id_group != null && priority != null && description != null && status != null && id_incident_type != null
				&& title_value != null && id_group_value != null && priority_value != null && description_value != null && status_value != null && id_incident_type_value != null ){


				var valid_priority_values = ['10','0','1','2','3','4'];	
				var valid_status_values = ['1','2','3','4','5','6', '7'];	
				

				if(name_form == '[select form]' ){				
					jQuery('#show-message_tickets').text('Select a form.').addClass('error').css('color', 'red');
				}
				else if(!jQuery.isNumeric(id_group_value) && id_group_value != '-'){
					jQuery('#show-message_tickets').text('ID Group is not a number.').addClass('error').css('color', 'red');
				}
				else if( jQuery.inArray(priority_value,valid_priority_values) === -1 && priority_value != '-'){
					jQuery('#show-message_tickets').text('The valid values for Priority are: 10, 0, 1, 2, 3, y 4.').addClass('error').css('color', 'red');
				}
				else if( jQuery.inArray(status_value,valid_status_values) === -1 && status_value != '-'){
					jQuery('#show-message_tickets').text('The valid values for Status are: 1, 2, 3, 4, 5, 6 y 7.').addClass('error').css('color', 'red');
				}
				else if(!jQuery.isNumeric(id_incident_type_value) && id_incident_type_value != '-'){
					jQuery('#show-message_tickets').text('ID Incident type is not a number.').addClass('error').css('color', 'red');
				}
				else{

					var data = {
						'action': 'set_data_form_tickets',
						'id_form' : id_form,
						'name_form' : name_form,

						'title' : title,
						'id_group' : id_group,
						'priority' : priority,
						'description' : description,
						'status' : status,
						'id_incident_type' : id_incident_type,
						'field1' : field1,
						'field2' : field2,
						'field3' : field3,
						'field4' : field4,
						'field5' : field5,
						'field6' : field6,

						'title_value' : title_value,
						'id_group_value' : id_group_value,
						'priority_value' : priority_value,
						'description_value' : description_value,
						'status_value' : status_value,
						'id_incident_type_value' : id_incident_type_value,
						'field1_value' : field1_value,
						'field2_value' : field2_value,
						'field3_value' : field3_value,
						'field4_value' : field4_value,
						'field5_value' : field5_value,
						'field6_value' : field6_value
					}


					jQuery.post(ajaxurl, data, function(response) {

						//alert('Got this from the server: ' + response);

						if(response != 1){
							// console.log(response);
							jQuery('#show-message_tickets').text('Error. Check if your form already exists in the table.').addClass('error').css('color', 'red');
						}
						else{
							//console.log('correct');
							jQuery('#show-message_tickets').text('It has been inserted successfully.').addClass('updated').css('color', 'green');
							jQuery('#show-message_tickets').submit();
						   	location.reload(true);

						}


					})


				}


			}
			else{

				jQuery('#show-message_tickets').text('You can not leave any empty fields.').addClass('error').css('color', 'red');

			}


		})


	}

	// ===== END === GET FIELDS from the TICKETS FORM ==================

// ===== END === JQUERY TICKETS Functions ====================== 
