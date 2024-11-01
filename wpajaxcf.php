<?php
/*
* Plugin Name:   WP Ajax Contact Form
* Plugin URI:    http://onlinewebapplication.com/2011/10/wp-ajax-contact-form.html
* Description:   This plugin mainly design for Ajax Contact Form, also this plugin sends mail using ajax and gather email list, have options page, custom css and form design usability. 
* Version:       2.2.2
 * Author:        Pankaj Jha
 * Author URI:    http://onlinewebapplication.com/
 *
 * License:       GNU General Public License, v2 (or newer)
 * License URI:  http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * This program was modified from Ajax Contact Form plugin, version 1.0.0, 
 * Copyright (C) 2011 Richard Gigs, released under the GNU General Public License.
 */

	// Install database table for wpajaxcf plugin
add_action("plugins_loaded","db_install");

function db_install() {
	$bloginfo = get_bloginfo( "admin_email" );
	add_option('wpajaxcf_recipient',$bloginfo);
	add_option('wpajaxcf_subject','Contact Form');
	add_option('wpajaxcf_confirm','Your message has been sent!');
	add_option('wpajaxcf_custom_css','');
	
	global $wpdb;
	$table_name = $wpdb->prefix. "wpajaxcf";

	$sql = "CREATE TABLE " .$table_name. "(
	`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`name` VARCHAR( 50 ) NULL ,
	`value` VARCHAR( 500 ) NULL ,
 	 PRIMARY KEY ( `id` )
	)";
	
	require_once(ABSPATH. "wp-admin/includes/upgrade.php");
	dbDelta($sql);

}

	// Install form table where your form will be kept
add_action("plugins_loaded","design_form_db");

function design_form_db() {
	global $wpdb;
	$table_name = $wpdb->prefix. "design_form";
	$table_exists = tableExists($table_name);
	if(!$table_exists) {
	
	$sql2 = "CREATE TABLE " .$table_name. "(
	`id` INT( 10 ) NOT NULL AUTO_INCREMENT ,
	`tname` VARCHAR( 10 ) NULL ,
	`tid` VARCHAR( 20 ) NULL ,
	`class` VARCHAR( 20 ) NULL ,
	`name` VARCHAR( 40 ) NULL ,
	`value` VARCHAR( 40 ) NULL ,
	`torder` VARCHAR( 40 ) NULL ,
	PRIMARY KEY ( `id` ) 
	)";
	
	require_once(ABSPATH. "wp-admin/includes/upgrade.php");
	dbDelta($sql2);
	  //}
	dbDelta("INSERT INTO `wp_design_form` (
				`id` ,
				`tname` ,
				`tid` ,
				`class` ,
				`name` ,
				`value` ,
				`torder`
				)
				VALUES (
				NULL , 'input', 'fname', NULL , 'fname', 'First Name', '1'
				);
	");
	dbDelta("INSERT INTO `wp_design_form` (
				`id` ,
				`tname` ,
				`tid` ,
				`class` ,
				`name` ,
				`value` ,
				`torder`
				)
				VALUES (
				NULL , 'input', 'lname', NULL , 'lname', 'Last Name', '2'
				);
	");
	dbDelta("INSERT INTO `wp_design_form` (
				`id` ,
				`tname` ,
				`tid` ,
				`class` ,
				`name` ,
				`value` ,
				`torder`
				)
				VALUES (
				NULL , 'input', 'email', NULL , 'email', 'Email', '3'
				);
	");
	dbDelta("INSERT INTO `wp_design_form` (
				`id` ,
				`tname` ,
				`tid` ,
				`class` ,
				`name` ,
				`value` ,
				`torder`
				)
				VALUES (
				NULL , 'textarea', 'msgarea', NULL , 'msgarea', 'Message', '99'
				);
	");
	
   }
}

	function tableExists($tablename) {
	 
		// Get a list of tables contained within the database.
		$result = mysql_list_tables(DB_NAME);
		$rcount = mysql_num_rows($result);
	 
		// Check each in list for a match.
		for ($i=0;$i<$rcount;$i++) {
			if (mysql_tablename($result, $i)==$tablename) return true;
		}
		return false;
	}

	// admin interface for wpajaxcf plugin
add_action('admin_menu','wpajaxcf_admin');

function wpajaxcf_admin() {
	
    add_menu_page( 'wpAjaxcf Options', 'wpAjaxcf Options', 'manage_options', 'wpajaxcf', 'wpajaxcf_options_page' );
    add_submenu_page( 'wpajaxcf', 'wpajaxcf settings', 'Settings', 'manage_options', 'wpajaxcf', 'wpajaxcf_options_page');
    add_submenu_page( 'wpajaxcf', 'Manage Contact List', 'Manage Contact List', 'manage_options', 'members_list', 'wpajaxcf_admin_page');
	add_submenu_page( 'wpajaxcf', 'Design Form', 'Design Form', 'manage_options', 'dform', 'design_form');
	//call register settings function
	add_action( 'admin_init', 'register_mysettings' );
	}
	
	function register_mysettings() {
		//register our settings
		register_setting( 'baw-settings-group', 'wpajaxcf_recipient' );
		register_setting( 'baw-settings-group', 'wpajaxcf_subject' );
		register_setting( 'baw-settings-group', 'wpajaxcf_confirm' );
		register_setting( 'baw-settings-group', 'wpajaxcf_custom_css' );
	}

	function design_form() {
	
	include_once('design_form.php');
	
	}
	
	function wpajaxcf_options_page() {

	include_once('options_page.php');

	}
	
	function wpajaxcf_admin_page() {
	
	include_once('email_list.php');
	
	}
	
	// add css file
add_action('wp_head','add_some_css');
function add_some_css() {
	?>
<style>
	<?php
	require_once('style.css');
	echo get_option('wpajaxcf_custom_css');
	?>
	</style>
<?php
	}
	
    // enqueue and localise scripts
    wp_enqueue_script( "my-ajax-handle", plugin_dir_url( __FILE__ ) . "wpajaxcf.js", array( "jquery" ) );
	//wp_enqueue_script( plugin_dir_url( __FILE__ ) . 'script.js' );
    wp_localize_script( "my-ajax-handle", "the_ajax_script", array( "ajaxurl" => admin_url( "admin-ajax.php" ) ) );

    // add actions
add_action( "wp_ajax_wpajaxcf", "ajax_action" );
add_action( "wp_ajax_nopriv_wpajaxcf", "ajax_action" );

	// Email Verification
function EmailValidation($email) { 
    $email = htmlspecialchars(stripslashes(strip_tags($email))); //parse unnecessary characters to prevent exploits
    
    if ( eregi ( '[a-z||0-9]@[a-z||0-9].[a-z]', $email ) ) { //checks to make sure the email address is in a valid format
    $domain = explode( "@", $email ); //get the domain name
        
        if ( @fsockopen ($domain[1],80,$errno,$errstr,3)) {
            //if the connection can be established, the email address is probabley valid
            return true;
            /*
            
            GENERATE A VERIFICATION EMAIL
            
            */
            
        } else {
            return false; //if a connection cannot be established return false
        }
    
    } else {
        return false; //if email address is an invalid format return false
    }
}	
    // Output function
function ajax_action(){
    global $wpdb;
	
	$check = $_POST['check'];
	$email = $_POST['email'];
	$msgarea = $_POST['msgarea'];
	$reply_email = get_option('wpajaxcf_recipient');
	$message ="<table border=\"0\" cellpadding=\"5\" cellspacing=\"0\">";

$confirm_string = get_option('wpajaxcf_confirm');
if($msgarea=='' || $msgarea==$confirm_string || $msgarea=='Message' || $msgarea=='email recipient address not found.' || $msgarea=='Please enter a valid email address.' || $msgarea=='please check your message again.') {
	echo "please check your message again.";
}
else if(EmailValidation($email)) {
	$form_table = $wpdb->prefix . "design_form";
	$form_fields = $wpdb->get_results("select * from $form_table order by torder ASC");
	$myrows = $wpdb->get_results("select name from $form_table order by torder ASC");
	$table_name = $wpdb->prefix . "wpajaxcf";

	foreach($myrows as $myrow) {
	// database insertion
	$wpdb->insert( $table_name, array( 'name' => $myrow->name, 'value' => $_POST[$myrow->name] ) );
	foreach($form_fields as $form_field) {
	if($form_field->name==$myrow->name)
	$message .= "<tr><td>".$form_field->value. ": </td><td>" .$_POST[$myrow->name]. "</td></tr>"; }
	}
		
	$from = explode('@',$email);
	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: '.$from[0].' <'.$email.'>' . "\r\n";
	$message .= "</table>";
	
	// send mail function
    if(mail(get_option('wpajaxcf_recipient'),get_option('wpajaxcf_subject'),$message,$headers))
	{
	echo get_option('wpajaxcf_confirm');
	if($check) {
	$headers_self  = 'MIME-Version: 1.0' . "\r\n";
	$headers_self .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers_self .= 'From: noreply' . "\r\n";
	mail($email,get_option('wpajaxcf_subject'),$message,$headers_self);	
	}
	}
	else
	echo "email recipient address not found.";

	}
	
	else {
	echo "Please enter a valid email address.";
	}
	
	die();
    }

    // add contact form
function ajax_contact_form(){
	global $wpdb;
	$table_name = $wpdb->prefix . "design_form";
	$myrows = $wpdb->get_results("select * from $table_name order by torder ASC"); 
    ?>
<form id="ajaxForm">
  <?php foreach($myrows as $myrow) { if($myrow->tname=='input') { ?>
  <p>
    <label for="<?php echo $myrow->tid; ?>"><?php echo $myrow->value; ?>:</label>
    <input id="<?php echo $myrow->tid; ?>" name="<?php echo $myrow->name; ?>" value="<?php echo $myrow->value; ?>" type="text" />
  </p>
  <?php } else { ?>
  <p>
    <label for="<?php echo $myrow->tid; ?>"><?php echo $myrow->value; ?>:</label>
    <textarea id="<?php echo $myrow->tid; ?>" name="<?php echo $myrow->name; ?>"><?php echo $myrow->value; ?></textarea>
  </p>
  <?php } } ?>
  <p>
    <label for="check">Want a copy of this mail to your address?:</label>
    <input id="check" name="check" type="checkbox" />
  </p>
  <p class="submit">
    <input name="action" type="hidden" value="wpajaxcf" />
    <input id="submit_button" value="Send" type="button" onClick="submit_me();" />
  </p>
</form>
<?php
    }
add_shortcode("wpajaxcf", "ajax_contact_form");
?>
