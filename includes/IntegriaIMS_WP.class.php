<?php
/*
Copyright (c) 2016-2016 Artica Soluciones Tecnologicas

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU Affero General Public License as
published by the Free Software Foundation, either version 3 of the
License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU Affero General Public License for more details.

You should have received a copy of the GNU Affero General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


class IntegriaIMS_WP {
	//=== INIT === ATRIBUTES ===========================================
	public $prefix = 'iims-wp::';

	private $acl_user_menu_entry = "manage_options"; // acl settings
	private $position_menu_entry = 75; //Under tools


	public $debug = 1;
	//=== END ==== ATRIBUTES ===========================================
	
	
	//=== INIT === SINGLETON CODE ======================================
	private static $instance = null;
	

	public static function getInstance() {
		if (!self::$instance instanceof self) {
			self::$instance = new self;
		}
		
		return self::$instance;
	}
	//=== END ==== SINGLETON CODE ======================================
	

	private function __construct() {
	}
	

	private function install() {

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();	

		$installed = get_option("installed", false);
		
		if (!$installed) {
			add_option($iims_wp->prefix . "installed", true);
			//add_option("iimswp-options", ''); // Is not necesary
		}
		
		// upgrade.php Contains the dbDelta function which will check if the table exists or not
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	
		// Table "form_data" for save data from Leads
		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data";
		$sql = "CREATE TABLE IF NOT EXISTS `$tablename` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`id_form` int(60) NOT NULL,
			`name_form` varchar(255) NOT NULL DEFAULT '',
			`name` varchar(255) NOT NULL DEFAULT '',
			`email` varchar(255) NOT NULL DEFAULT '',
			`phone` varchar(255) NOT NULL DEFAULT '',
			`company` varchar(255) NOT NULL DEFAULT '',
			`know_us` varchar(255) NOT NULL DEFAULT '',
			`message` varchar(255) NOT NULL DEFAULT '',
			`language` varchar(255) NOT NULL DEFAULT '',
			`id_product` int(60) NOT NULL, 
			`tags` varchar(255) NOT NULL DEFAULT '',			
			PRIMARY KEY (`id`),
			UNIQUE KEY `id_form` (`id_form`)
			);";

		dbDelta($sql); 	// The wordpress has the function dbDelta that create (or update if it created previously).	

		// Table "form_data_tickets" for save data from Tickets
		$tablename_tickets = $wpdb->prefix . $iims_wp->prefix . "form_data_tickets";
		$sql = "CREATE TABLE IF NOT EXISTS `$tablename_tickets` (
			`id` INT NOT NULL AUTO_INCREMENT,
			`id_form` int(60) NOT NULL,
			`name_form` varchar(255) NOT NULL DEFAULT '',
			`title` varchar(255) NOT NULL DEFAULT '',
			`id_group` varchar(255) NOT NULL DEFAULT '', 
			`priority` varchar(255) NOT NULL DEFAULT '',
			`description` varchar(255) NOT NULL DEFAULT '',
			`status` varchar(255) NOT NULL DEFAULT '',		
			`id_incident_type` varchar(255) NOT NULL DEFAULT '',		
			`field1` varchar(255) NOT NULL DEFAULT '',		
			`field2` varchar(255) NOT NULL DEFAULT '',		
			`field3` varchar(255) NOT NULL DEFAULT '',		
			`field4` varchar(255) NOT NULL DEFAULT '',		
			`field5` varchar(255) NOT NULL DEFAULT '',		
			`field6` varchar(255) NOT NULL DEFAULT '',		
			`title_value` varchar(255) NOT NULL DEFAULT '',
			`id_group_value` varchar(255) NOT NULL DEFAULT '', 
			`priority_value` varchar(255) NOT NULL DEFAULT '',
			`description_value` varchar(255) NOT NULL DEFAULT '',
			`status_value` varchar(255) NOT NULL DEFAULT '',
			`id_incident_type_value` varchar(255) NOT NULL DEFAULT '',
			`field1_value` varchar(255) NOT NULL DEFAULT '',		
			`field2_value` varchar(255) NOT NULL DEFAULT '',		
			`field3_value` varchar(255) NOT NULL DEFAULT '',		
			`field4_value` varchar(255) NOT NULL DEFAULT '',		
			`field5_value` varchar(255) NOT NULL DEFAULT '',		
			`field6_value` varchar(255) NOT NULL DEFAULT '',							
			PRIMARY KEY (`id`),
			UNIQUE KEY `id_form` (`id_form`)
			);";

		dbDelta($sql); 	
	
	}

	
	//=== INIT === HOOKS CODE ==========================================
	public static function activation() {
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		// Check if installed
		$iims_wp->install();	
		// Only active the plugin again

	}
	

	public static function deactivation() {
		error_log( "Deactivation" );
	}
	

	public static function uninstall() {
		IntegriaIMS_WP::deactivation();
		error_log( "Uninstall" );
	}


	public static function init() {
		$iims_wp = IntegriaIMS_WP::getInstance();

		$iims_wp->setup(); // Load Setup at start

	}


	public static function admin_init() {
		$iims_wp = IntegriaIMS_WP::getInstance();

	
			// Create the widget
			add_action('wp_dashboard_setup',
				array("IIMS_Widget_Setup", "show_setup"));
			
			//Added settings
			register_setting(
				"iimswp-settings-group-setup",
				"iimswp-options-setup",
				array("IntegriaIMS_WP", "sanitize_options_setup")); 

	}
	
	// Added script
	public static function my_wp_enqueue_script(){
		
		wp_enqueue_script('jquery-ui-dialog');
		
	    wp_enqueue_script(
			'admin_scripts',
			plugin_dir_url( __FILE__ ) . '../js/iims_admin_js.js'); //My JQuery functions


	    //Forms Validation
		//wp_enqueue_script('jquery-form');
		
   		// Registering Scripts
    	//wp_register_script('google-hosted-jquery', '//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js', false);
    	//wp_register_script('jquery-validation-plugin', 'http://ajax.aspnetcdn.com/ajax/jquery.validate/1.11.1/jquery.validate.min.js', array('google-hosted-jquery'));

    	// Enqueueing Scripts to the head section
    	//wp_enqueue_script('google-hosted-jquery');
    	//wp_enqueue_script('jquery-validation-plugin');


	}


	public static function my_wp_enqueue_style(){
		
		wp_enqueue_style("wp-jquery-ui-dialog");

	}


	//Plugin Formidable is required
	public static function show_message_formidable_required() {		
			
	    if (!is_plugin_active('formidable/formidable.php')) {

    	    echo '<div id="message" class="notice notice-warning">
        			<p>Plugin <a href="https://es.wordpress.org/plugins/formidable/">Formidable</a> is required! You must install and activate Formidable plugin.</p>
        	     </div>';
	    }
	 
	}

	//=== END ==== HOOKS CODE ==========================================
	
	
	public function use_trailing_slashes() {
		return '/' === substr( get_option( 'permalink_structure' ), -1, 1 );
	}
	

	public function user_trailingslashit( $string ) {
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		return $iims_wp->use_trailing_slashes() ?
			trailingslashit( $string ) : untrailingslashit( $string );
	}
	

	public function debug($var) {
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		if (!$iims_wp->debug)
			return;
		
		$more_info = '';
		if (is_string($var)) {
			$more_info = 'size: ' . strlen($var);
		}
		elseif (is_bool($var)) {
			$more_info = 'val: ' .
				($var ? 'true' : 'false');
		}
		elseif (is_null($var)) {
			$more_info = 'is null';
		}
		elseif (is_array($var)) {
			$more_info = count($var);
		}
		
		ob_start();
		echo "(" . gettype($var) . ") " . $more_info . "\n";
		print_r($var);
		echo "\n\n";
		$output = ob_get_clean();
		
		error_log($output);
	}
	

	// === MENUS ===========================================

	public static function add_admin_menu_entries() {
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		$icon = plugins_url("images/icon.png", str_replace( "includes/", "", __FILE__));		

		add_menu_page(
			_("IntegriaIMS WP : Setup"),
			_("IntegriaIMS WP"),
			$iims_wp->acl_user_menu_entry,
			"iims_wp_admin_menu",
			array("IIMS_AdminPages", "show_setup"),
			$icon,
			$iims_wp->position_menu_entry);

		add_submenu_page(
			"iims_wp_admin_menu",
			_("IntegriaIMS WP : Setup"), 
			_("Setup"),
			$iims_wp->acl_user_menu_entry,
			"iims_wp_admin_menu",
			array("IIMS_AdminPages", "show_setup"));
		
		add_submenu_page(
			"iims_wp_admin_menu",
			_("IntegriaIMS WP : Leads"), 
			_("Leads"),
			$iims_wp->acl_user_menu_entry,
			"iims_wp_admin_menu_leads",
			array("IIMS_AdminPages", "show_leads"));
		
		add_submenu_page(
			"iims_wp_admin_menu",
			_("IntegriaIMS WP : Tickets"),
			_("Tickets"),
			$iims_wp->acl_user_menu_entry,
			"iims_wp_admin_menu_tickets",
			array("IIMS_AdminPages", "show_tickets"));
	
	}

	// === END === MENUS =============================================


	// === Options SANITIZE and DEFAULT =============================

	// Set default values for Setup options 
	private function set_default_options() {
		$default_options = array();
		
		$default_options['api_url'] = "";
		$default_options['user_id'] = "";
		$default_options['user_pass'] = "";
		$default_options['api_pass'] = "";
 
		return $default_options; 
	}


	public static function sanitize_options_setup($options) {
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		if (!is_array($options) || empty($options) || (false === $options)){
			return $iims_wp->set_default_options();	
		}


		if (!isset($options['api_url']))
			$options['api_url'] = '';
		if (!isset($options['user_id']))
			$options['user_id'] = '';
		if (!isset($options['user_pass']))
			$options['user_pass'] = '';
		if (!isset($options['api_pass']))
			$options['api_pass'] = '';
			
		
		return $options; 
	}


	// === END === Options SANITIZE and DEFAULT ===================


	// === Table OPTIONS of Wordpress (wp_options) ================

	// This function get the setup options and send its to function set_setup() 
	public static function setup() { 
		global $wpdb;
		
		$iims_wp = IntegriaIMS_WP::getInstance();
		
		$options = get_option('iimswp-options-setup');
		$options = $iims_wp->sanitize_options_setup($options);


		if (empty($setup))
			$setup = array();

		$iims_wp->set_setup($options);

	}


	// This function is called by setup() and make the update in the array option
	private function set_setup($options) {
		update_option('iimswp-options-setup', $options); 
		// If the option doesn't exist, this creates it (only if register_settings doesn't work)
	}

	// === END === Table OPTIONS of Wordpress (wp_options)=========


	// === SEND TO INTEGRIA LEADS ====================================

	// 'frm_after_create_entry' is a hook of formidable plugin
	// This hook allows you to do something with the data entered in a form after it is submitted.
	// Intercepts the form when the lead (potencial) fills it and sends it
	public static function frm_save_integria($entry_id, $form_id){ //These parameters are required
				
		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		/* I get $form_id, so I can compare it with the table form_data and form_data ticket to know if in which table it is.
		So I can know if the form is a Lead or a Ticket. */

		$tablename_data = $wpdb->prefix . $iims_wp->prefix ."form_data"; 
		$table_data_multi = $wpdb->get_results("SELECT * FROM `" . $tablename_data . "` WHERE id_form LIKE ". $form_id ." ");

		$tablename_data_tickets = $wpdb->prefix . $iims_wp->prefix ."form_data_tickets"; 
		$table_data_tickets_multi = $wpdb->get_results("SELECT * FROM `" . $tablename_data_tickets . "` WHERE id_form LIKE ". $form_id ." ");

		// With this if-else I can know if the form is a Lead or a Ticket 
		if( count($table_data_multi) != 0 AND count($table_data_tickets_multi) != 0 ){
			$iims_wp->debug('Duplicate Form.');
		}	

		else if(count($table_data_multi) != 0){
			//$iims_wp->debug('Table form_data');
			// TABLE form_data (I created this table and it contains the leads data)
			$tablename_data = $wpdb->prefix . $iims_wp->prefix ."form_data"; 
			$table_data_multi = $wpdb->get_results("SELECT * FROM `" . $tablename_data . "` ");
			//$table_data_multi is a object StdClass

			$table_data = array(); //This array is for keep the data of form_data out of the foreach (array_merge)
			foreach ($table_data_multi as $i => $value) {

			 	if($table_data_multi[$i]->id_form == $form_id){

					$table_data_array = array(
						'indice' => $table_data_multi[$i]
					);

					$table_data = array_merge($table_data,$table_data_array);
					$table_data = $table_data['indice'];
			 	}


			 } 
			//Now $table_data is an array well structured


			// TABLE frm_fields (I need it to get $id_field so I can compare it with $form_id later) 
			$tablename_fields = $wpdb->prefix . "frm_fields";
			$table_fields = $wpdb->get_results("SELECT * FROM `" . $tablename_fields . "` ");

			$table_fields = json_decode(json_encode($table_fields));

			$fields = array(); //This array is for keep the data of frm_fields out of the foreach (array_merge)
			$data_send_array = array(); //This array is for keep the data received by POST request out of the foreach (array_merge)
			foreach ($table_fields as $key => $i) {

				$array_fields = array( 
				     "id" => "$i->id" ,
				     "name" => "$i->name" ,
				     "form_id" =>"$i->form_id"  
				); //unir array_fields en un solo array
				$fields = array_merge($fields,$array_fields);
				//Now $fields is an array well structured , so I can create the 3 variables I need
					
				$id_field = $fields['form_id'];
				$form_id_field = $fields['id'];
				$name_field = htmlentities($fields['name']);

				//I get $form_id like a parameter in this function, so I only receive the id of the form that the user is filling and sending
				//I compare $form_id with $id_field (table frm_fields) to get the data post request that this form only
				if ($form_id == $id_field){

					$language = $table_data->language; 
					$product= $table_data->id_product; 
					$integria_tag= $table_data->tags;  
					//These 3 variables contain the data (table form_data) that the admin previously inserted in the input fields

					$post = sanitize_text_field( $_POST['item_meta'][$form_id_field]);
					$data_send = array(
						 $name_field => $post  
					);	//Here I can an array for each data, so I need merge it in a only array
					$data_send_array = array_merge($data_send_array,$data_send);
					//Now $data_send_array is an array well structured (with post request data)

				} //if 


			} //foreach
			//$iims_wp->debug($data_send_array); //compare with $table_data

			$table_data_array = json_decode(json_encode($table_data), True);
			//$iims_wp->debug($table_data_array);

			//Delete the elements of the array that I'm not going to compare to avoid the error when comparing an array of 11 elements with one of 6
			unset($table_data_array['id'],$table_data_array['id_form'],$table_data_array['name_form'],$table_data_array['language'],$table_data_array['id_product'],$table_data_array['tags']);
			//$iims_wp->debug($table_data_array);

			$merged = array();
				foreach ($table_data_array as $key => $value) {
						
					$index = array_search($value,$table_data_array,false);
			
						if($index == $key){

							$field_name = array(
								$index => $data_send_array[$value]
							);

							$merged = array_merge($merged,$field_name);
							
						}

				} //foreach


			$name = $merged['name'];	
			$email = $merged['email'];
			$phone = $merged['phone'];
			$company =  $merged['company'];
			$know_us =  $merged['know_us'];
			$message = $merged['message'];	


			// We will use | to separate fields. So we need to filter in incoming data
			$name = str_replace("|","",$name);
			$phone = str_replace("|","",$phone);
			$company = str_replace("|","",$company);
			$know_us = str_replace("|","",$know_us);
			$message = str_replace("|","",$message);


		    //Data of setup
		   	$options = get_option('iimswp-options-setup');
			$options = $iims_wp->sanitize_options_setup($options);

		    $api_url = $options['api_url'];
		    $user_id = $options['user_id'];
		    $user_pass = $options['user_pass'];
		    $api_pass = $options['api_pass'];


		    $body=array(
		        'user' => $user_id,
		        'pass' => $api_pass,
				'token' => '|',
		        'user_pass' => $user_pass,
		        'op' => "create_lead",
		        'params' => $name."|".$company."|".$email."|||||".$phone."|||".$language."|".$message."|".$product."|||%3B|".$integria_tag);

			$args = array(
				'body' => $body,
				'timeout' => '5',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'cookies' => array()
			);

			$response = wp_remote_post( $api_url, $args );

		}

		// === ELSE IF that is executed if the form is a ticket ================
		else if (count($table_data_tickets_multi) != 0){
			//$iims_wp->debug('Table form_data_tickets');
			// TABLE form_data_tickets 
			$tablename_data = $wpdb->prefix . $iims_wp->prefix ."form_data_tickets"; 
			$table_data_multi = $wpdb->get_results("SELECT * FROM `" . $tablename_data . "` ");

			$table_data = array();
			foreach ($table_data_multi as $i => $value) {

			 	if($table_data_multi[$i]->id_form == $form_id){

					$table_data2 = array(
						'index' => $table_data_multi[$i]
					);

					$table_data = array_merge($table_data,$table_data2);
					$table_data = $table_data['index'];
					// $table_data : To remove the index to the object Stdclass. This is an array with the appropriate form.

			 	}

			} //foreach
			$table_data_array = json_decode(json_encode($table_data), True); //To turn Stdclass into a normal array (table form_data_tickets)

			// TABLE frm_fields 
			$tablename_fields = $wpdb->prefix ."frm_fields"; 
			$table_fields = $wpdb->get_results("SELECT * FROM `" . $tablename_fields . "` ");
			$table_fields_array2 = json_decode(json_encode($table_fields), True); //To turn it into a normal array

			$fields2 = array();
			$data_send_array2 = array();
			foreach ($table_fields_array2 as $key => $i) {

				$array_fields2 = array(  
					"id" => $i['id'],   
				    "name" => $i['name']  
				); 

				$fields2 = array_merge($fields2,$array_fields2);

				$id = $fields2['id'];
				$name = trim(htmlentities($fields2['name']));
			

				// With the item_id we know which are the fields that belong to the same form
				$tablename_item_metas = $wpdb->prefix ."frm_item_metas"; 
				$table_item_metas = $wpdb->get_results("SELECT * FROM `" . $tablename_item_metas . "` ");
				$table_data_array2 = json_decode(json_encode($table_item_metas), True); //To turn it into a normal array (Contains the entire table)


				// ===== THIS BUILDS THE ARRAY WITH ALL THE POSTS THAT ARE SENT FROM THE FORM
				$fields = array();
				$data_send_array = array();
					foreach ($table_data_array2 as $key => $i) {

						$array_fields = array( 
							"item_id" => $i['item_id'] ,  // To unite them because it's the common value
						     "field_id" => $i['field_id'] ,  // This is the id of each field of the form
						     "meta_value" => $i['meta_value']  // Value that the clients wrote on the form
						); 
						$fields = array_merge($fields,$array_fields);
						// Join $array_fields into a single array

						$item_id = $fields['item_id'];
						$form_field_id = $fields['field_id'];
						$meta_value = htmlentities($fields['meta_value']);


						if ($item_id == $entry_id){ 
							
							$post = sanitize_text_field( $_POST['item_meta'][$form_field_id]);

								if($form_field_id == $id){
								
									// Search $name in $table_data_array (table form_data_tickets)
									$index = array_search($name, $table_data_array);
			
									$data_send[] = array(	
										$index => $post
									); // This array is to know what field of the form corresponds to the field name (title, descripction...)
									// I have created a multidimensional array because it is associative
									// array_merge doesn't work for associative arrays
									
								}//if

						} //if 

					}//foreach


			} //foreach

			// $data_send : Array with all the posts that are sent from the form (multidimensional array)
			$data_send_array = call_user_func_array('array_merge', $data_send); // To turn it into a normal array
			// $data_send_array : Array with all the posts that are sent from the form (normal array)

			// ===== END === THIS BUILDS THE ARRAY WITH ALL THE POSTS THAT ARE SENT FROM THE FORM


			//Check if the variables exists. If the admin has not created the forms in Integria Plugin, and He inserts the form in a page og his web, when the user fills and sends the form, it will give an error.
			if(!isset($table_data_array['title_value'], $table_data_array['id_group_value'], $table_data_array['priority_value'], $table_data_array['description_value'], $table_data_array['status_value'], $table_data_array['id_incident_type_value']) ){

				$table_data_array['title_value'] = null ;
				$data_send_array['title'] = null;
				$title = null;

				$table_data_array['id_group_value'] = null ;
				$data_send_array['id_group'] = null ;
				$id_group = null;

				$table_data_array['priority_value'] = null ;
				$data_send_array['priority'] = null ;
				$priority = null;

				$table_data_array['description_value'] = null ;
				$data_send_array['description'] = null ;
				$description = null;

				$table_data_array['status_value'] = null ;
				$data_send_array['status'] = null ;
				$status = null;

				$table_data_array['id_incident_type_value'] = null ;
				$data_send_array['id_incident_type'] = null ;
				$id_incident_type = null;
			}
			else{

				// If the text field is empty, send the post of the form; if not, send the value of my table
				if($table_data_array['title_value'] == '' OR $table_data_array['title_value'] == '-'){
					$title = $data_send_array['title']; // Send what the clients write in the form
				}
				else{
					$title = $table_data_array['title_value'];	// If not, send that the admin write in the input text
				}


				if($table_data_array['id_group_value'] == '' OR $table_data_array['id_group_value'] == '-'){
					$id_group = $data_send_array['id_group'];
				}
				else{					
					$id_group = $table_data_array['id_group_value'];
				}


				if($table_data_array['priority_value'] == '' OR $table_data_array['priority_value'] == '-'){
					$priority = $data_send_array['priority'];
				}
				else{					
					$priority = $table_data_array['priority_value'];
				}


				if($table_data_array['description_value'] == '' OR $table_data_array['description_value'] == '-'){
					$description = $data_send_array['description'];
				}
				else{					
					$description = $table_data_array['description_value'];
				}


				if($table_data_array['status_value'] == '' OR $table_data_array['status_value'] == '-'){
					$status = $data_send_array['status'];
				}
				else{					
					$status = $table_data_array['status_value'];
				}


				if($table_data_array['id_incident_type_value'] == '' OR $table_data_array['id_incident_type_value'] == '-'){
					$id_incident_type = $data_send_array['id_incident_type'];
				}
				else{					
					$id_incident_type = $table_data_array['id_incident_type_value'];
				}
				//Check if id_incident_type exist. If not the custom fields will be null.
				if ($id_incident_type !== NULL && $id_incident_type !== ''){
					//Check if the custom fields exists.
					if(!isset($table_data_array['field1_value']) ){
						$table_data_array['field1_value'] = null ;
						$data_send_array['field1'] = null;
						$field1 = null;
					} 
					else {
						if($table_data_array['field1_value'] == '' OR $table_data_array['field1_value'] == '-'){
							if (isset($data_send_array['field1']))
							$field1 = $data_send_array['field1'];
							else
							$field1=null;
						}
						else{
							$field1 = $table_data_array['field1_value'];
						}
					}

					if(!isset($table_data_array['field2_value']) ){
						$table_data_array['field2_value'] = null ;
						$data_send_array['field2'] = null;
						$field2 = null;
					} 
					else {
						if($table_data_array['field2_value'] == '' OR $table_data_array['field2_value'] == '-'){
							if (isset($data_send_array['field2']))
							$field2 = $data_send_array['field2'];
							else 
							$field2 = null;
						}
						else{
							$field2 = $table_data_array['field2_value'];
						}
					}

					if(!isset($table_data_array['field3_value']) ){
						$table_data_array['field3_value'] = null ;
						$data_send_array['field3'] = null;
						$field3 = null;
					} 
					else {
						if($table_data_array['field3_value'] == '' OR $table_data_array['field3_value'] == '-'){
							if (isset($data_send_array['field3']))
							$field3 = $data_send_array['field3'];
							else 
							$field3 = null;
						}
						else{
							$field3 = $table_data_array['field3_value'];
						}
					}

					if(!isset($table_data_array['field4_value']) ){
						$table_data_array['field4_value'] = null ;
						$data_send_array['field4'] = null;
						$field4 = null;
					} 
					else {
						if($table_data_array['field4_value'] == '' OR $table_data_array['field4_value'] == '-'){
							if (isset($data_send_array['field4']))
							$field4 = $data_send_array['field4'];
							else 
							$field4 = null;
						}
						else{
							$field4 = $table_data_array['field4_value'];
						}
					}

					if(!isset($table_data_array['field5_value']) ){
						$table_data_array['field5_value'] = null ;
						$data_send_array['field5'] = null;
						$field5 = null;
					} 
					else {
						if($table_data_array['field5_value'] == '' OR $table_data_array['field5_value'] == '-'){
						if (isset($data_send_array['field5']))
							$field5 = $data_send_array['field5'];
							else 
							$field5 = null;
						}
						else{
							$field5 = $table_data_array['field5_value'];
						}
					}

					if(!isset($table_data_array['field6_value']) ){
						$table_data_array['field6_value'] = null ;
						$data_send_array['field6'] = null;
						$field6 = null;
					} 
					else {
						if($table_data_array['field6_value'] == '' OR $table_data_array['field6_value'] == '-'){
							if (isset($data_send_array['field6']))
							$field6 = $data_send_array['field6'];
							else 
							$field6 = null;
						}
						else{
							$field6 = $table_data_array['field6_value'];
						}
					}

				}

			}


			// We will use | to separate fields. So we need to filter in incoming data
			$title = str_replace("|","",$title);
			$id_group = str_replace("|","",$id_group);
			$priority = str_replace("|","",$priority);
			$description = str_replace("|","",$description);
			$status = str_replace("|","",$status);
			$id_incident_type = str_replace("|","",$id_incident_type);
			
			$field1 = str_replace("|","",$field1);
			$field2 = str_replace("|","",$field2);
			$field3 = str_replace("|","",$field3);
			$field4 = str_replace("|","",$field4);
			$field5 = str_replace("|","",$field5);
			$field6 = str_replace("|","",$field6);


		    //Data from setup
		   	$options = get_option('iimswp-options-setup');
			$options = $iims_wp->sanitize_options_setup($options);

		    $api_url = $options['api_url'];
		    $user_id = $options['user_id'];
		    $user_pass = $options['user_pass'];
		    $api_pass = $options['api_pass'];

				$id_inventory='';
				$email='';
				$owner='';
				$id_ticket_parent='';
				$extra_data = '';
				$resolution = '';
				$extra_data2 = '';
				$extra_data3 = '';
				$extra_data4 = '';

		    $body=array(
		        'user' => $user_id,
		        'pass' => $api_pass,
				'token' => '|',
		        'user_pass' => $user_pass,
		        'op' => "create_incident",
		        'params' => $title."|".$id_group."|".$priority."|".$description."|".$id_inventory."|".$id_incident_type."|".$email."|".$owner."|".$id_ticket_parent."|".$status."|".$extra_data."|".$resolution."|".$extra_data2."|".$extra_data3."|".$extra_data4."|".$field1."|".$field2."|".$field3."|".$field4."|".$field5."|".$field6);


			$args = array(
				'body' => $body,
				'timeout' => '5',
				'redirection' => '5',
				'httpversion' => '1.0',
				'blocking' => true,
				'headers' => array(),
				'cookies' => array()
			);


			$response = wp_remote_post( $api_url, $args );

		} //else if

		// === ELSE to know if the form is a ticket or a lead =============

		else{
			$iims_wp->debug('Does not exist that form in any table.');
		}


	}

	// === END === SEND TO INTEGRIA LEADS ============================


	// === AJAX === FUNCTIONS =================================

	//===== Check API Version Integria ===============================

	public static function ajax_check_api_version(){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$api_url = sanitize_text_field($_POST['api_url']);

		$iims_wp->check_api_version($api_url);

	}

	private function check_api_version($api_url){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$version = @file_get_contents($api_url.'?info=version');


		if(!$version === false){
			echo $version;
		}
		else{
			return false;
		}


	}

	//===== END === Check API Version Integria ===============================


	//===== Check User Integria ===============================

	public static function ajax_check_connection_integria(){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$api_url = sanitize_text_field($_POST['api_url']);
		$user_id = sanitize_text_field($_POST['user_id']);
		$user_pass = sanitize_text_field($_POST['user_pass']);
		$api_pass = sanitize_text_field($_POST['api_pass']);

		$iims_wp->check_connection_integria($api_url,$user_id,$user_pass,$api_pass);

	}

	private function check_connection_integria($api_url,$user_id,$user_pass,$api_pass){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		//Resultado: Devuelve 1 si la autenticación es correcta, 0 si no
	    $body=array(
	        'user' => $user_id,
	        'pass' => $api_pass,
			'token' => '|',
	        'user_pass' => $user_pass,
	        'op' => "validate_user",
	       // 'params' => $user_id."|".$api_pass."|".$user_pass);
	        'params' => $user_id."|".$user_pass);

		$args = array(
			'body' => $body
			/*'timeout' => '5',
			'redirection' => '5',
			'httpversion' => '1.0',
			'blocking' => true,
			'headers' => array(),
			'cookies' => array()*/
		);


		$response = wp_remote_post( $api_url, $args );


		if(is_array($response)){

			if($response['body'] == 1){
				//$iims_wp->debug('correct');
				echo true; // It must be an echo for de jQuery response
			}
			else{
				//$iims_wp->debug('incorrect');
				echo false; // It must be an echo for de jQuery response
			}


		}else{

			 $error_message = $response->get_error_message();
			 $iims_wp->debug("Something went wrong: $error_message");
			 //echo 'Something went wrong: ' . $error_message;
			 echo false; // It must be an echo for de jQuery response

		}


		wp_die(); //It is necesary for de jQuery response


	}

	//===== END === Check User Integria ===================


	// ===== Table forms ==================================

	public static function ajax_delete_row_form_data(){
	
		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();
	
		$id_input_table = sanitize_text_field($_POST['id_input_table']);

		$iims_wp->delete_row_form_data($id_input_table);

	}

	private function delete_row_form_data($id_input_table){
		
		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data";	

		$wpdb->delete(
					$tablename,
					array('id' => $id_input_table)
				);

	}

	// ===== END === Table forms ==================================

	// ===== Form Create Leads ==================================
	public static function ajax_set_data_form() {

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();
	
		$id_form = intval($_POST['id_form']);
		$name_form = strval($_POST['name_form']);

		$name = sanitize_text_field($_POST['name']);
		$email = sanitize_text_field($_POST['email']);
		$phone = sanitize_text_field($_POST['phone']);
		$company = sanitize_text_field($_POST['company']);
		$know_us = sanitize_text_field($_POST['know_us']);
		$message = sanitize_text_field($_POST['message']);

		$language = sanitize_text_field($_POST['language']);
		$id_product = sanitize_text_field($_POST['id_product']);
		$tags = sanitize_text_field($_POST['tags']);


		if( $name != '' && $email != '' && $phone != '' && $company != '' && $know_us != '' && $message != '' && $language != '' && $id_product != '' 
			&& $name != null && $email != null && $phone != null && $company != null
			&& $know_us != null && $message != null && $language != null && $id_product != null ){ 

				$iims_wp->set_data_form($id_form,$name_form,$name,$email,$phone,$company,$know_us,$message,$language,$id_product,$tags);

		}


		
	} 


	private function set_data_form($id_form,$name_form,$name,$email,$phone,$company,$know_us,$message,$language,$id_product,$tags){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data";	

		$return = $wpdb->insert(
							$tablename,
							array(
								'id_form' => $id_form,
								'name_form' => $name_form,
								'name' => htmlentities($name),
								'email' => htmlentities($email),
								'phone' => htmlentities($phone),
								'company' => htmlentities($company),
								'know_us' => htmlentities($know_us),
								'message' => htmlentities($message),
								'language' => htmlentities($language),
								'id_product' => $id_product,
								'tags' => htmlentities($tags)),
							array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s')
						);	


		// $return If it is correct give 1 and if it fails give void
		//wp_send_json($return);
		echo $return;
		wp_die(); // this is required to terminate immediately and return a proper response (to jQuery)


	}

 	// ===== END === Form Create Leads ==================================


	// ===== Table forms Tickets ==================================

	public static function ajax_delete_row_form_data_tickets(){
	
		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();
	
		$id_input_table = sanitize_text_field($_POST['id_input_table']);

		$iims_wp->delete_row_form_data_tickets($id_input_table);

	}


	private function delete_row_form_data_tickets($id_input_table){
		
		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data_tickets";	

		$wpdb->delete(
					$tablename,
					array('id' => $id_input_table)
				);

	}

	// ===== END === Table forms Tickets ==================================


	// ===== Form Create Tickets ==================================
	public static function ajax_set_data_form_tickets() {

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();
	
		$id_form = intval($_POST['id_form']);
		$name_form = strval($_POST['name_form']);
		
		$title = sanitize_text_field($_POST['title']);
		$id_group = sanitize_text_field($_POST['id_group']);
		$priority = sanitize_text_field($_POST['priority']);
		$description = sanitize_text_field($_POST['description']);
		$status = sanitize_text_field($_POST['status']);
		$id_incident_type = sanitize_text_field($_POST['id_incident_type']);
		$field1 = sanitize_text_field($_POST['field1']);
		$field2 = sanitize_text_field($_POST['field2']);
		$field3 = sanitize_text_field($_POST['field3']);
		$field4 = sanitize_text_field($_POST['field4']);
		$field5 = sanitize_text_field($_POST['field5']);
		$field6 = sanitize_text_field($_POST['field6']);
		$title_value = sanitize_text_field($_POST['title_value']);
		$id_group_value = sanitize_text_field($_POST['id_group_value']); 
		$priority_value = sanitize_text_field($_POST['priority_value']);
		$description_value = sanitize_text_field($_POST['description_value']);
		$status_value = sanitize_text_field($_POST['status_value']);
		$id_incident_type_value = sanitize_text_field($_POST['id_incident_type_value']);
		$field1_value = sanitize_text_field($_POST['field1_value']);
		$field2_value = sanitize_text_field($_POST['field2_value']);
		$field3_value = sanitize_text_field($_POST['field3_value']);
		$field4_value = sanitize_text_field($_POST['field4_value']);
		$field5_value = sanitize_text_field($_POST['field5_value']);
		$field6_value = sanitize_text_field($_POST['field6_value']);


		if( $title != '' && $id_group != '' && $priority != '' && $description != '' && $status != '' && 
			$title_value != '' && $id_group_value != '' && $priority_value != '' && $description_value != '' && $status_value != '' && 
			$title != null && $id_group != null && $priority != null && $description != null && $status != null && 
			$title_value != null && $id_group_value != null && $priority_value != null && $description_value != null && $status_value != null ){ 
			//Check if the incident type exist. If not, custom fields will be null
			if ($id_incident_type == '' || $id_incident_type == NULL || $id_incident_type_value == '' || $id_incident_type_value == NULL){
				$field1 = NULL;
				$field2 = NULL;
				$field3 = NULL;
				$field4 = NULL;
				$field5 = NULL;
				$field6 = NULL;
				$field1_value = NULL;
				$field2_value = NULL;
				$field3_value = NULL;
				$field4_value = NULL;
				$field5_value = NULL;
				$field6_value = NULL;
				}
			$iims_wp->set_data_form_tickets($id_form,$name_form,$title,$id_group,$priority,$description,$status,$id_incident_type,$field1,$field2,$field3,$field4,$field5,$field6,$title_value,$id_group_value,$priority_value,$description_value,$status_value,$id_incident_type_value,$field1_value,$field2_value,$field3_value,$field4_value,$field5_value,$field6_value);

		}


	} 


	private function set_data_form_tickets($id_form,$name_form,$title,$id_group,$priority,$description,$status,$id_incident_type,$field1,$field2,$field3,$field4,$field5,$field6,$title_value,$id_group_value,$priority_value,$description_value,$status_value,$id_incident_type_value,$field1_value,$field2_value,$field3_value,$field4_value,$field5_value,$field6_value){

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data_tickets";	

		$return = $wpdb->insert(
			$tablename,
			array(
				'id_form' => $id_form,
				'name_form' => $name_form,
				'title' => htmlentities($title),
				'id_group' => htmlentities($id_group),
				'priority' => htmlentities($priority),
				'description' => htmlentities($description),
				'status' => htmlentities($status),
				'id_incident_type' => htmlentities($id_incident_type),
				'field1' => htmlentities($field1),
				'field2' => htmlentities($field2),
				'field3' => htmlentities($field3),
				'field4' => htmlentities($field4),
				'field5' => htmlentities($field5),
				'field6' => htmlentities($field6),
				'title_value' => htmlentities($title_value),
				'id_group_value' => htmlentities($id_group_value),
				'priority_value' => htmlentities($priority_value),
				'description_value' => htmlentities($description_value),
				'status_value' => htmlentities($status_value),
				'id_incident_type_value' => htmlentities($id_incident_type_value),
				'field1_value' => htmlentities($field1_value),
				'field2_value' => htmlentities($field2_value),
				'field3_value' => htmlentities($field3_value),
				'field4_value' => htmlentities($field4_value),
				'field5_value' => htmlentities($field5_value),
				'field6_value' => htmlentities($field6_value)),
			array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s', '%s'));

		
		//wp_send_json($return);
		echo $return;
		wp_die(); // this is required to terminate immediately and return a proper response (to jQuery)


	}

 	// ===== END === Form Create Tickets ==================================


	// === END === AJAX FUNCTIONS ====================================

	
}





?>