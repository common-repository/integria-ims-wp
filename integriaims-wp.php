<?php
/**
 * @package IntegriaIMS WP
 * @version 1.1
 */
/*
Plugin Name: Integria IMS integration for Wordpress
Plugin URI: https://github.com/articaST/integriaims-wp
Description: Integration of wordpress with Integria IMS: lead generation and tickets using Integria IMS restful API and Formidable plugin.
Author: Artica ST
Version: 1.1
Author URI: http://artica.es/
Text Domain: integriaims-wp
License: GPLv2
Copyright: (c) 2017 Artica Soluciones Tecnologicas
*/

//if ( ! defined( 'ABSPATH' ) ) die();

//=== INIT === INCLUDES ================================================
require_once(plugin_dir_path(__FILE__) . "/includes/IntegriaIMS_WP.class.php");
require_once(plugin_dir_path(__FILE__) . "/includes/IIMS_AdminPages.class.php");
require_once(plugin_dir_path(__FILE__) . "/includes/IIMS_Widget_Setup.class.php");
//=== END ==== INCLUDES ================================================


//=== INIT === HOOKS FOR INSTALL (OR REGISTER) AND UNINSTALL ===========
register_activation_hook(__FILE__, array('IntegriaIMS_WP', 'activation'));
register_deactivation_hook(__FILE__, array('IntegriaIMS_WP', 'deactivation'));
//=== END ==== HOOKS FOR INSTALL (OR REGISTER) AND UNINSTALL ===========


//=== HOOKS ============================================================
add_action('admin_notices', array('IntegriaIMS_WP', 'show_message_formidable_required'));
add_action('admin_menu', array('IntegriaIMS_WP', 'add_admin_menu_entries'));
add_action('init', array('IntegriaIMS_WP', 'init'));
add_action('admin_init', array('IntegriaIMS_WP', 'admin_init'));
add_action('frm_after_create_entry', array('IntegriaIMS_WP', 'frm_save_integria'), 20, 2);
//frm_after_create_entry This hook allows you to do something with the data entered in a form after it is submitted.
//=== END ==== HOOKS ===================================================

//=== AJAX HOOKS ===
add_action( 'wp_print_scripts', array('IntegriaIMS_WP','my_wp_enqueue_script' ));
add_action( 'wp_print_styles', array('IntegriaIMS_WP','my_wp_enqueue_style' ));

add_action('wp_ajax_set_data_form', array('IntegriaIMS_WP', 'ajax_set_data_form'));
add_action('wp_ajax_delete_row_form_data', array('IntegriaIMS_WP', 'ajax_delete_row_form_data'));
add_action('wp_ajax_set_data_form_tickets', array('IntegriaIMS_WP', 'ajax_set_data_form_tickets'));
add_action('wp_ajax_delete_row_form_data_tickets', array('IntegriaIMS_WP', 'ajax_delete_row_form_data_tickets'));
add_action('wp_ajax_check_connection_integria', array('IntegriaIMS_WP', 'ajax_check_connection_integria'));
add_action('wp_ajax_check_api_version', array('IntegriaIMS_WP', 'ajax_check_api_version'));
//===END AJAX HOOKS ===



?>
