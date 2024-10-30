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



class IIMS_AdminPages {
	
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


	//=== SETUP DASHBOARD VIEW =========================================
	public static function show_setup() {

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		?>

		<div class="wrap">
			<h2><?php esc_html_e("Setup");?></h2>
		</div>

		<form method="post" action="options.php" name="formulario-setup" id="formulario-setup">
				<?php settings_fields('iimswp-settings-group-setup');?>
				<?php $options = get_option('iimswp-options-setup');?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">
						<?php esc_html_e("Integria API url");?>
					</th>
					<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span>
									<?php esc_html_e("Integria API url");?>
								</span>
							</legend>
							<label for="iimswp-options-setup[api_url]">
								<input
									class="regular-text"
									type="text"
									name="iimswp-options-setup[api_url]"
									id="api_url"
									value="<?php echo esc_url($options['api_url']);?>"
								/>
								<input
									type="button" name="button-api" id="button-api"
									class="button button-secondary"
									style="margin-left: 20px;"
									value="<?php esc_attr_e('Check API Version');?>" 
									onclick="check_api_version()"
								/>
								<img id="loading_version" style="display: none;" src="<?php echo esc_url(admin_url('images/spinner.gif')) ?>" alt="loading" />
								<img id="true_version" style="display: none;" src="<?php echo esc_url(admin_url('images/yes.png')) ?>" alt="yes" />
								<img id="false_version" style="display: none;" src="<?php echo esc_url(admin_url('images/no.png')) ?>" alt="no" />
								<span style="margin-left: 20px;" id="check_api_version"></span>
							</label>
							<p style="font-style: italic; font-size: 12px; color: #ababab;">Example: http://192.168.70.128/integria/include/api.php</p>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<?php esc_html_e("User ID");?>
					</th>
						<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span>
									<?php esc_html_e("User ID");?>
								</span>
							</legend>
							<label for="iimswp-options-setup[user_id]">
								<input
									class="regular-text"
									type="text"
									name="iimswp-options-setup[user_id]"
									id="user_id"
									value="<?php echo esc_attr($options['user_id']);?>"
									/>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<?php esc_html_e("User pass");?>
					</th>
						<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span>
									<?php esc_html_e("User pass");?>
								</span>
							</legend>
							<label for="iimswp-options-setup[user_pass]">
								<input
									class="regular-text"
									type="password"
									type="password"
									name="iimswp-options-setup[user_pass]"
									id="user_pass"
									value="<?php echo esc_attr($options['user_pass']);?>"
									/>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row">
						<?php esc_html_e("API pass");?>
					</th>
						<td>
						<fieldset>
							<legend class="screen-reader-text">
								<span>
									<?php esc_html_e("API pass");?>
								</span>
							</legend>
							<label for="iimswp-options-setup[api_pass]">
								<input
									class="regular-text"
									type="password"
									name="iimswp-options-setup[api_pass]"
									id="api_pass"
									value="<?php echo esc_attr($options['api_pass']);?>"
									/>
							</label>
						</fieldset>
					</td>
				</tr>
				<tr>
					<th scope="row">
						
					</th>
					<td>
						<input
							type="button" name="check_connection" id="check_connection"
							class="button button-secondary"
							value="<?php esc_attr_e('Check connection');?>" 
							onclick="check_connection_integria()" 
						/>
						<img id="loading_setup" style="display: none;" src="<?php echo esc_url(admin_url('images/spinner.gif')) ?>" alt="loading" />
						<img id="true_setup" style="display: none;" src="<?php echo esc_url(admin_url( 'images/yes.png')) ?>" alt="yes" />
						<img id="false_setup" style="display: none;" src="<?php echo esc_url(admin_url( 'images/no.png')) ?>" alt="no" />
					</td>
				</tr>
			</table>
			<p class="submit">
				<input
					type="submit" name="submit" id="submit"
					class="button button-primary"
					value="<?php esc_attr_e("Save Changes");?>" />
			</p>
		</form>

		<?php
	}
	//=== END ==== SETUP DASHBOARD VIEW ================================


	//=== LEADS VIEW ===================================================

	public static function show_leads() {

		global $wpdb;
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data";
		$list = $wpdb->get_results(" SELECT * FROM `$tablename` "); 

		?>
		<div class="wrap">
			<h2><?php esc_html_e("Current Leads Forms");?></h2>

		<!-- ===== Show table form_data ================================ -->

			<?php

			if (empty($list))
				$list = array();

			?>
				<table id="list_data_form" class="widefat striped">
					<thead>
						<tr>
							<th style="text-align: center;"><?php esc_html_e("ID Form");?></th>
							<th style="text-align: center;"><?php esc_html_e("Name Form");?></th>
							<th style="text-align: center;"><?php esc_html_e("Name");?></th>
							<th style="text-align: center;"><?php esc_html_e("Email");?></th>
							<th style="text-align: center;"><?php esc_html_e("Phone");?></th>
							<th style="text-align: center;"><?php esc_html_e("Company");?></th>
							<th style="text-align: center;"><?php esc_html_e("How Know us");?></th>
							<th style="text-align: center;"><?php esc_html_e("Message");?></th>
							<th style="text-align: center;"><?php esc_html_e("Language");?></th>
							<th style="text-align: center;"><?php esc_html_e("ID Product");?></th>
							<th style="text-align: center;"><?php esc_html_e("Tags");?></th>
							<th style="text-align: center;"></th>
						</tr>
					</thead>
			<?php

			if (empty($list)) {
				?>
					<tbody>
						<tr>
							<td colspan="10">
							<p><strong><?php echo esc_html_e("No data available.");?></strong></p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			else {
				?>
					<tbody>
						<?php
						foreach ($list as $entry) {
							?>
							<tr id="id_fila_<?php echo esc_attr($entry->id); ?>">
								<td style="text-align: center;"><?php esc_html_e($entry->id_form);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->name_form);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->name);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->email);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->phone);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->company);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->know_us);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->message);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->language);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->id_product);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->tags);?></td>
								<td style="text-align: center;">
									<img src=" <?php echo esc_url(admin_url( 'images/no.png'))?> " id='btn_add_<?php echo esc_attr($entry->id); ?>' title="Delete" onclick="delete_form_data(<?php echo esc_attr($entry->id); ?>);" />
								</td>
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>
				<?php
			}
			?>
		</div>

		<!-- ===== END === Show table form_data  ======================= -->

		<p><strong>Important:</strong> You must first specify here the form fields before inserting a form into a web page.</p>
		<br/>
		<p><strong>Formidable:</strong> </p>
		<p>When you create a form, remember to set the fields 'Name', 'Email' and 'Company' as required.</p>

		<h2>Create Lead:</h2>

		<?php

		if (!is_plugin_active('formidable/formidable.php')) {
			echo '<p style="color:red;">You must install and activate Formidable plugin for create a Ticket.</p>';
		}
		else{
			echo '	
					<input
						type="button" name="create_form_data"
						id="create_form_data"
						class="button button-primary"
						value="Create" 
						onclick="create_toggle()" 
					/>
				';
		}

		?>

		<!-- ===== Show/Hide Create Leads Form ========================= -->	
		<div id="create_toggle" style="display: none;">
		<p><strong>Note:</strong></p>
		<ul style="list-style-type: disc; padding-left: 33px;">
			<li>You can't put the same form field for several options.</li>
		<li> Remember that if a form is already in the table, you can not re-create or modify it. You must delete it and create it again.</li>
		</ul>	
		<br/>
		<p>Select a form to load the options.</p>

		<?php

		$tablename = $wpdb->prefix . "frm_fields";
		$tablename_forms = $wpdb->prefix . "frm_forms";

		$table_fields = $wpdb->get_results("SELECT * FROM `" . $tablename . "` ");
		$table_fields = json_decode(json_encode($table_fields));

		$fields = array();
		foreach ($table_fields as $key => $i) {

			$array_fields = array(
			      "$i->name" => "$i->form_id"
			);
			$fields = array_merge($fields,$array_fields);

		}
		
		echo "<input type='hidden' id ='hidden_leads' value='".json_encode($fields)."'/>";

		$table_forms = $wpdb->get_results("SELECT * FROM `" . $tablename_forms . "` WHERE status LIKE 'published' AND is_template LIKE '0' AND default_template LIKE '0' ");

		if (empty($table_fields)) {
			?>
				<p><strong><?php esc_html_e("No forms were found.");?></strong></p>
			<?php
		}
		else {
			?>
		<div class="wrap">
			<form method="post" action="" name="formulario-leads" id="formulario-leads">
				<?php settings_fields('iimswp-settings-group-leads');?>
				<?php $options = get_option('iimswp-options-leads');?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Form");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Form");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[id_form]">
								<select name="options[foo-lead]" id="id_form" onchange="var var_id_form = this.value; load_values(var_id_form)">
 									<option selected>[select form]</option> 
									<?php 
										foreach ($table_forms as $forms) { 
									?>
										    
										    <option value="<?php echo esc_attr($forms->id); ?>"> 
										    <?php echo esc_attr($forms->name); ?>
										    </option> 	    		    	
									<?php
										}
									?> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Name");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Name");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[name]">
								<select name="options[foo]" id="name" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Email");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Email");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[email]">
								<select name="options[foo]" id="email" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Phone");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Phone");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[phone]">
								<select name="options[foo]" id="phone" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Company");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Company");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[company]">
								<select name="options[foo]" id="company" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("How did you know us");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("How did you know us");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[know_us]">
								<select name="options[foo]" id="know_us" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Message");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Message");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[message]">
								<select name="options[foo]" id="message-l" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Language");?>
						</th>
							<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Language");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[language]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-leads[language]"
										value=""
										id="language"
										placeholder="de, en_GB, es, fr, pl, ru, zh_CN"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("ID Product ");?>
						</th>
							<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("ID Product ");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[id_product]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-leads[id_product]"
										value=""
										id="id_product"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Tags (opcional) ");?>
						</th>
							<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Tags (opcional)");?>
									</span>
								</legend>
								<label for="iimswp-options-leads[tags]">
									<input
										class="regular-text"
										type="text"
										name="iimswp-options-leads[tags]"
										value=""
										id="tags"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
				</table>
				<p class="submit">
					<input
						type="button" name="submit" id="submit-data"
						class="button button-primary"
						value="<?php esc_attr_e('Save Changes');?>" onclick="get_data_form()"/>
				</p>
			</form>
		</div>

		<!-- ===== END === Show/Hide Create Leads Form ================= -->	
			<p id="show-message"></p>
		</div>

			<?php
		} //end show_leads else

	} //end function show_leads

	//=== END === LEADS VIEW ===========================================



	//=== TICKETS VIEW==================================================

	public static function show_tickets() {

		global $wpdb;	
		$iims_wp = IntegriaIMS_WP::getInstance();

		$tablename = $wpdb->prefix . $iims_wp->prefix . "form_data_tickets";
		$list = $wpdb->get_results(" SELECT * FROM `$tablename` "); 

		?>
		<div class="wrap">
			<h2><?php esc_html_e("Current Tickets Forms");?></h2>
		
		<!-- ===== Show table form_data_tickets ======================== -->

			<?php

			if (empty($list))
				$list = array();

			?>
				<table id="list_data_form_tickets" class="widefat striped">
					<thead>
						<tr>
							<th style="text-align: center;"><?php esc_html_e("ID Form");?></th>
							<th style="text-align: center;"><?php esc_html_e("Name Form");?></th>
							<th style="text-align: center;"><?php esc_html_e("Title");?></th>
							<th style="text-align: center;"><?php esc_html_e("ID Group");?></th>
							<th style="text-align: center;"><?php esc_html_e("Priority");?></th>
							<th style="text-align: center;"><?php esc_html_e("Description");?></th>
							<th style="text-align: center;"><?php esc_html_e("Status");?></th>
							<th style="text-align: center;"><?php esc_html_e("ID incident type");?></th>
							<!-- <?php for ($i = 1; $i <= 6; $i++) { ?> 
							<th style="text-align: center;"><?php esc_html_e("field".$i);?></th>
							<?php } ?> -->
							<th style="text-align: center;"><?php esc_html_e("Title_value");?></th>
							<th style="text-align: center;"><?php esc_html_e("ID Group_value");?></th>
							<th style="text-align: center;"><?php esc_html_e("Priority_value");?></th>
							<th style="text-align: center;"><?php esc_html_e("Description_value");?></th>
							<th style="text-align: center;"><?php esc_html_e("Status_value");?></th>
							<th style="text-align: center;"><?php esc_html_e("ID incident type_value");?></th>
							<!-- <?php for ($i = 1; $i <= 6; $i++) { ?> 
							<th style="text-align: center;"><?php esc_html_e("field".$i."_value");?></th>
							<?php } ?> -->
							<th style="text-align: center;"></th>
						</tr>
					</thead>
			<?php
			
			if (empty($list)) {
				?>
					<tbody>
						<tr>
							<td colspan="10">
							<p><strong><?php echo "No data available.";?></strong></p>
							</td>
						</tr>
					</tbody>
				</table>
				<?php
			}
			else {
				?>

					<tbody>
						<?php
						foreach ($list as $entry) {
							?>
							<tr id="id_fila_tickets_<?php echo esc_attr($entry->id); ?>">
								<td style="text-align: center;"><?php esc_html_e($entry->id_form);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->name_form);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->title);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->id_group);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->priority);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->description);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->status);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->id_incident_type);?></td>
								<!-- <td style="text-align: center;"><?php esc_html_e($entry->field1);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field2);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field3);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field4);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field5);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field6);?></td> -->
								<td style="text-align: center;"><?php esc_html_e($entry->title_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->id_group_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->priority_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->description_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->status_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->id_incident_type_value);?></td>
								<!-- <td style="text-align: center;"><?php esc_html_e($entry->field1_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field2_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field3_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field4_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field5_value);?></td>
								<td style="text-align: center;"><?php esc_html_e($entry->field6_value);?></td> -->
								<td style="text-align: center;">
									<img src=" <?php echo esc_url(admin_url( 'images/no.png'))?> " id='btn_add_tickets_<?php echo esc_attr($entry->id); ?>' title="Delete" onclick="delete_form_data_tickets(<?php echo esc_attr($entry->id); ?>);" />
								</td>						
							</tr>
							<?php
						}
						?>
					</tbody>
				</table>

				<?php
			}
			?>

		</div>

		<!-- ===== END === Show table form_data_tickets ================ -->

		<p><strong>Important:</strong> You must first specify here the form fields before inserting a form into a web page.</p>
		<br/>
		<p><strong>Formidable:</strong></p>
		<p> When you create a form, remember to set all the fields as required.</p>

		<h2>Tickets creation:</h2>

		<?php

		if (!is_plugin_active('formidable/formidable.php')) {
			echo '<p style="color:red;">You must install and activate Formidable plugin for create a Ticket.</p>';
		}
		else{
			echo '	
					<input
						type="button" name="create_form_data_tickets"
						id="create_form_data_tickets"
						class="button button-primary"
						value="Create Ticket" 
						onclick="create_toggle()" 
					/>
				';
		}

		?>

		<!-- ===== Show/Hide Create Tickets Form ======================= -->	
		<div id="create_toggle_tickets" style="display: none;">

		<p><strong>Note:</strong></p>
		<ul style="list-style-type: disc; padding-left: 33px;">
			<li> Remember that if a form is already in the table, you can not re-create or modify it. You must delete it and create it again.</li>
		</ul>	
		<br/>
		<p>Select a form to load the options.</p>
		<p>Note that the text field prevails over the select field.</p>

		<?php

		$tablename = $wpdb->prefix . "frm_fields";
		$tablename_forms = $wpdb->prefix . "frm_forms";

		$table_fields = $wpdb->get_results("SELECT * FROM `" . $tablename . "` ");

		$table_fields = json_decode(json_encode($table_fields));

		$fields = array();
		foreach ($table_fields as $key => $i) {

			$array_fields = array(
			      "$i->name" => "$i->form_id"
			);
			$fields = array_merge($fields,$array_fields);

		}
		echo "<input type='hidden' id ='hidden_tickets' value='".json_encode($fields)."'/>";


		$table_forms = $wpdb->get_results("SELECT * FROM `" . $tablename_forms . "` WHERE status LIKE 'published' AND is_template LIKE '0' AND default_template LIKE '0' ");


		if (empty($table_fields)) {
			?>
				<p><strong><?php esc_html_e("No forms were found.");?></strong></p>
			<?php
		}
		else {

			?>

		<div class="wrap">
			
			<form method="post" action="" name="formulario-tickets" id="formulario-tickets">
				<?php settings_fields('iimswp-settings-group-tickets');?>
				<?php $options = get_option('iimswp-options-tickets');?>

				<table class="form-table">
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Form");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Form");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[id_form]">
								<select name="options[foo-ticket]" id="id_form_tickets" onchange="var var_id_form = this.value; load_values_tickets(var_id_form)">
 									<option selected>[select form]</option> 
									<?php 
										foreach ($table_forms as $forms) { 
									?>
										    
										    <option value="<?php echo esc_attr($forms->id); ?>"> 
										    <?php echo esc_attr($forms->name); ?>
										    </option> 	    
										    	
									<?php
										}
									?> 
								</select>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Title");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Title");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[title]">
								<select name="options[foo-t]" id="title" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Title");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[Title]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[title-static]"
										value=""
										id="title-static"
										/>
								</label>
							</fieldset>
						</td>		
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("ID group");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("ID group");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[id_group]">
								<select name="options[foo-t]" id="id_group" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("ID group");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[ID group]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[id_group-static]"
										value=""
										id="id_group-static"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Priority");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Priority");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[priority]">
								<select name="options[foo-t]" id="priority" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Priority");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[Priority]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[priority-static]"
										value=""
										placeholder="10(Maintenance), 0(Informative), 1(Low), 2(Medium), 3(Serious), 4(Very serious)"
										id="priority-static"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Description");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Description");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[description]">
								<select name="options[foo-t]" id="description" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Description");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[Description]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[description-static]"
										value=""
										id="description-static"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Status");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Status");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[status]">
								<select name="options[foo-t]" id="status" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Status");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[Status]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[status-static]"
										value=""
										placeholder="1(New), 2(Unconfirmed), 3(Assigned), 4(Re-opened), 5(Pending to be closed), 6(Pending on a third person), 7(closed)"
										id="status-static"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("ID incident type");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("ID incident type");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[id_incident_type]">
								<select name="options[foo-t]" id="id_incident_type" >
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("ID incident type");?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[id_incident_type]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[id_incident_type-static]"
										value=""
										placeholder="0(Without type)"
										id="id_incident_type-static"
										/>
								</label>
							</fieldset>
						</td>
					</tr>
					
					<?php for ($i = 1; $i <= 6; $i++) { ?> 
					<tr valign="top">
						<th scope="row">
							<?php esc_html_e("Field".$i." (opcional)");?>
						</th>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Field".$i);?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[<?php echo 'field'.$i; ?>]">
								<select name="options[foo-t]" <?php echo ' id="field'.$i.'"'; ?>>
									<option selected></option> 
								</select>
								</label>
							</fieldset>
						</td>
						<td>
							<fieldset>
								<legend class="screen-reader-text">
									<span>
										<?php esc_html_e("Field".$i);?>
									</span>
								</legend>
								<label for="iimswp-options-tickets[<?php echo 'Field'.$i; ?>]">
									<input
										class="regular-text "
										type="text"
										name="iimswp-options-tickets[<?php echo 'field'.$i; ?>-static]"
										value=""
										id=<?php echo '"field'.$i.'-static"'; ?>
										/>
								</label>
							</fieldset>
						</td>
					</tr>
				<?php } ?>
				</table>
				<p class="submit">
				<input
					type="button" name="submit" id="data-submit-tickets"
					class="button button-primary"
					value="<?php esc_attr_e("Save Changes");?>" onclick="get_data_form_tickets()"/>
				</p>
			</form>
		</div>

		<!-- ===== END === Show/Hide Create Tickets Form ================== -->	
			<p id="show-message_tickets"></p>
		</div>

			<?php
		}

	}

	//=== END === TICKETS VIEW =========================================



} 
// === END === CLASS IIMS_AdminPages

?>