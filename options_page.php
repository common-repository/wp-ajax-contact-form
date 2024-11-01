<div class="wrap">
<h2>WP Ajax Contact Form Settings</h2>
<a href="http://onlinewebapplication.com/2011/10/wp-ajax-contact-form.html" target="_blank">Visit plugin site for installation and how to use steps/FAQ</a>
<style>
form.mysettings textarea.css {
width:250px;
height:160px;
}
form.mysettings input[type=text] {
width:250px;
} 
</style>
<form method="post" action="options.php" class="mysettings">
    <?php settings_fields( 'baw-settings-group' ); ?>
    <?php //do_settings( 'baw-settings-group' ); ?>
    <table class="form-table">
        <tr valign="top">
        <th scope="row">Email Recipient:</th>
        <td><input type="text" name="wpajaxcf_recipient" value="<?php echo get_option('wpajaxcf_recipient'); ?>" /></td>
        </tr>
         
        <tr valign="top">
        <th scope="row">Email Subject</th>
        <td><input type="text" name="wpajaxcf_subject" value="<?php echo get_option('wpajaxcf_subject'); ?>" /></td>
        </tr>
        
        <tr valign="top">
        <th scope="row">Confirmation Email Subject:</th>
        <td><input type="text" name="wpajaxcf_confirm" value="<?php echo get_option('wpajaxcf_confirm'); ?>" /></td>
        </tr>
		<tr valign="top">
        <th scope="row">Custom CSS:</th>
        <td><textarea name="wpajaxcf_custom_css" class="css"><?php echo get_option('wpajaxcf_custom_css'); ?></textarea></td>
        </tr>
    </table>
    
    <p class="submit">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    </p>
<br/>
This plugin is free, If you realy like this plugin request you to write about this plugin. For any further query please contact me here <a href="http://onlinewebapplication.com/2011/10/wp-ajax-contact-form.html" target="_blank"></a>

</form>
</div>
