<?php
/**
 * @package Websima Telegram Bot
 * @version 1.3
 */
/*
Plugin Name: Teletter Telegram Newsletter
Plugin URI: http://websima.com/teletter
Description: Send Newsletter from Telegram Bot, user can subscribe to your site from Telegram Bot.
Author: Websima Creative Agency
Version: 1.3
Author URI: http://websima.com
*/
add_action( 'plugins_loaded', 'tbot_load_textdomain' );
function tbot_load_textdomain() {
  load_plugin_textdomain( 'tbot', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
}
add_action('admin_menu', 'tbot_custom_menu_page');

function tbot_custom_menu_page()
{
	add_menu_page(__('Telegram API', 'tbot'), __('Telegram API', 'tbot'), 'manage_options', 'telegram_api', 'telegram_api_options_page', plugins_url('teletter-telegram-newsletter/includes/dashicon.png') , 25);
	add_submenu_page('telegram_api', __( 'subscribers', 'tbot' ), __( 'subscribers', 'tbot' ), 'manage_options', 'edit.php?post_type=subscriber');
	add_submenu_page('telegram_api',__( 'Log', 'tbot' ), __( 'Log', 'tbot' ), 'manage_options', 'teletter_log', 'teletter_log_panel');
}

function teletter_log_panel()
{
	if (isset($_GET['tbotclear'])) {
		delete_option('wp_teletter_log');
	//	wp_redirect(esc_url(remove_query_arg('tbotclear')));
		exit;
	}
?>
	<div class="wrap"><h2><?php printf(__( "Messages History", "tbot" )); ?><a href="admin.php?page=teletter_log&tbotclear=1" class="add-new-h2"><?php printf(__( "Clear Log", "tbot" )); ?></a></h2>
    <table class="widefat fixed" cellspacing="0">
    <thead>
    <tr>
        <th style="width: 15%;" class="manage-column" scope="col"><?php printf(__( "Type", "tbot" )); ?></th>
        <th style="width: 15%;" class="manage-column" scope="col"><?php printf(__( "Date", "tbot" )); ?></th>
        <th style="width: 15%;" class="manage-column" scope="col"><?php printf(__( "Update Number", "tbot" )); ?></th>
        <th style="width: 15%;" class="manage-column" scope="col"><?php printf(__( "Author", "tbot" )); ?></th>
        <th id="columnname" class="manage-column" scope="col"><?php printf(__( "Description", "tbot" )); ?></th>
    </tr>
    </thead>

    <tbody><?php echo get_option('wp_teletter_log'); ?></tbody>
</table></div>
<?php
}
function teletter_log($action, $update_id,$chat_id, $text)
{
	update_option('wp_teletter_log', '<tr>
            <td>' . $action . '</td>
            <td>' . date('m/d/Y H:i:s ', time()) . '</td>
            <td>' . sanitize_text_field($update_id) . '</td>
            <td>' . sanitize_text_field($chat_id) . '</td>
            <td>' . sanitize_text_field($text) . '</td>
        </tr>' . get_option('wp_teletter_log'));
}


include_once('includes/send-functions.php');
include_once('includes/api-settings.php');
include_once('includes/pagetemplate.php');
include_once('includes/users-save.php');
include_once('includes/getupdates.php');
include_once('includes/publishsend.php');
include_once('includes/notification-metabox.php');


$options = get_option( 'tbot_settings' );
$method = $options['tbot_select_method'];
$currency = $options['tbot_select_updates_time'];
if ($method == 'getupdates') {
	// Add function to register event to WordPress init
	add_action( 'init', 'getupdates_cron'); 

	// Function which will register the event
	function getupdates_cron() {
$options = get_option( 'tbot_settings' );
$currency = $options['tbot_select_updates_time'];
    // Make sure this event hasn't been scheduled
		if( !wp_next_scheduled( 'getupdates' ) ) {
        // Schedule the event
		if ($currency == 'hourly') {
			wp_schedule_event( time(), 'hourly', 'getupdates' );
		} elseif ($currency == 'twicedaily') {
			wp_schedule_event( time(), 'twicedaily', 'getupdates' );
		} elseif ($currency == 'daily') {
			wp_schedule_event( time(), 'daily', 'getupdates' );
		}
        
		}
	}
} else {
	$timestamp = wp_next_scheduled( 'getupdates' );
	// Un-schedule the event
	wp_unschedule_event( $timestamp, 'getupdates' );
}
$time = time();
$time = $time+100;
if(wp_next_scheduled('getupdates') && $time>wp_next_scheduled('getupdates')) {
add_action( 'init', 'getupdates' );
}
add_site_option( 'lastupdateid', '1');

?>