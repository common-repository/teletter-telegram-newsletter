<?php
add_action( 'admin_init', 'tbot_settings_init' );


function tbot_settings_init(  ) { 

	register_setting( 'pluginPage', 'tbot_settings' );

	add_settings_section(
		'tbot_pluginPage_section', 
		__( 'Get Telegram API ready', 'tbot' ), 
		'tbot_settings_section_callback', 
		'pluginPage'
	);

	add_settings_field( 
		'tbot_text_token', 
		__( 'Enter Your Telegram Bot Token', 'tbot' ), 
		'tbot_text_token_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_select_method', 
		__( 'Use getUpdates or Webhook', 'tbot' ), 
		'tbot_select_method_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
    add_settings_field( 
		'tbot_select_updates_time', 
		__( 'getUpdates Recurrence', 'tbot' ), 
		'tbot_select_updates_time_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_select_limit', 
		__( 'getUpdates Limit in every recurrence', 'tbot' ), 
		'tbot_select_limit_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_select_sendlimit', 
		__( 'Split Query Users', 'tbot' ), 
		'tbot_select_sendlimit_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_select_users', 
		__( 'Send Newsletter to All users or Active Users', 'tbot' ), 
		'tbot_select_users_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_text_newsubscriber', 
		__( 'Welcome Message to New Subscriber', 'tbot' ), 
		'tbot_text_newsubscriber_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_text_unsubscribe_command', 
		__( 'Command to Unsubscribe', 'tbot' ), 
		'tbot_text_unsubscribe_command_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_text_unsubscribe', 
		__( 'Message to unsubscriber', 'tbot' ), 
		'tbot_text_unsubscribe_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_text_admin', 
		__( 'Admin Password', 'tbot' ), 
		'tbot_text_admin_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	add_settings_field( 
		'tbot_select_admin_update', 
		__( 'Send Updates to Admins', 'tbot' ), 
		'tbot_select_admin_update_render', 
		'pluginPage', 
		'tbot_pluginPage_section' 
	);
	
$args = array(
   'public'   => true
);
$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types( $args, $output, $operator ); 
foreach ( $post_types  as $post_type ) {
	if ($post_type == 'attachment' || $post_type == 'subscriber') {} else {
	add_settings_field( 
		'tbt_'.$post_type, 
		__( 'Send Notification for ', 'tbot' ).$post_type, 
		'tbot_post_types_render', 
		'pluginPage', 
		'tbot_pluginPage_section',
array( 'type' => $post_type )		
	);
	}
}

}

function tbot_text_token_render(  ) { 
	$options = get_option( 'tbot_settings' );
	?>
	<input  style='direction:ltr;' type='text' name='tbot_settings[tbot_text_token]' value='<?php echo $options['tbot_text_token']; ?>'>
	<?php

}
function tbot_select_method_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_method]'>
		<option value='getupdates' <?php selected( $options['tbot_select_method'], 'getupdates' ); ?>><?php printf(__( 'getUpdates', 'tbot' )); ?></option>
		<option value='manual' <?php selected( $options['tbot_select_method'], 'manual' ); ?>><?php printf(__( 'Manual', 'tbot' )); ?></option>
		<option disabled value='webhook' <?php selected( $options['tbot_select_method'], 'webhook' ); ?>><?php printf(__( 'Webhook', 'tbot' )); ?></option>
	</select>

<?php

}
function tbot_select_updates_time_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_updates_time]'>
		<option value='hourly' <?php selected( $options['tbot_select_updates_time'], 'hourly' ); ?>><?php printf(__( 'Hourly', 'tbot' )); ?></option>
		<option value='twicedaily' <?php selected( $options['tbot_select_updates_time'], 'twicedaily' ); ?>><?php printf(__( 'Twice Daily', 'tbot' )); ?></option>
		<option value='daily' <?php selected( $options['tbot_select_updates_time'], 'daily' ); ?>><?php printf(__( 'Daily', 'tbot' )); ?></option>
	</select>

<?php

}
function tbot_select_sendlimit_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_sendlimit]'>
		<option value='nolimit' <?php selected( $options['tbot_select_sendlimit'], 'nolimit' ); ?>><?php printf(__( 'No Limit', 'tbot' )); ?></option>
		<option value='200' <?php selected( $options['tbot_select_sendlimit'], '200' ); ?>><?php printf(__( '200 Messages', 'tbot' )); ?></option>
		<option value='100' <?php selected( $options['tbot_select_sendlimit'], '100' ); ?>><?php printf(__( '100 Messages', 'tbot' )); ?></option>
		<option value='50' <?php selected( $options['tbot_select_sendlimit'], '50' ); ?>><?php printf(__( '50 Messages', 'tbot' )); ?></option>
		<option value='20' <?php selected( $options['tbot_select_sendlimit'], '20' ); ?>><?php printf(__( '20 Messages', 'tbot' )); ?></option>
	</select>
		<p><?php echo __( 'choose lower numbers to prevent Request Timed out on sharing hosts.', 'tbot' ); ?></p>
<?php

}
function tbot_select_limit_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_limit]'>
		<option value='nolimit' <?php selected( $options['tbot_select_limit'], 'nolimit' ); ?>><?php printf(__( 'No Limit', 'tbot' )); ?></option>
		<option value='100' <?php selected( $options['tbot_select_limit'], '100' ); ?>><?php printf(__( '100 new updates', 'tbot' )); ?></option>
		<option value='50' <?php selected( $options['tbot_select_limit'], '50' ); ?>><?php printf(__( '50 new updates', 'tbot' )); ?></option>
		<option value='20' <?php selected( $options['tbot_select_limit'], '20' ); ?>><?php printf(__( '20 new updates', 'tbot' )); ?></option>
	</select>

<?php

}
function tbot_select_users_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_users]'>
		<option value='all' <?php selected( $options['tbot_select_users'], 'all' ); ?>><?php printf(__( 'All Users', 'tbot' )); ?></option>
		<option value='active' <?php selected( $options['tbot_select_users'], 'active' ); ?>><?php printf(__( 'Active Users', 'tbot' )); ?></option>
	</select>

<?php

}
function tbot_text_newsubscriber_render(  ) { 
	$options = get_option( 'tbot_settings' );
	?>
	<textarea cols='40' rows='5' name='tbot_settings[tbot_text_newsubscriber]'><?php echo $options['tbot_text_newsubscriber']; ?></textarea>
	<?php

}
function tbot_text_unsubscribe_render(  ) { 
	$options = get_option( 'tbot_settings' );
	?>
	<textarea cols='40' rows='5' name='tbot_settings[tbot_text_unsubscribe]'><?php echo $options['tbot_text_unsubscribe']; ?></textarea>
	<?php

}
function tbot_text_unsubscribe_command_render(  ) { 
	$options = get_option( 'tbot_settings' );
	?>
	<input style='direction:ltr;' type='text' name='tbot_settings[tbot_text_unsubscribe_command]' value='<?php echo $options['tbot_text_unsubscribe_command']; ?>'>
	<?php

}
function tbot_text_admin_render(  ) { 
	$options = get_option( 'tbot_settings' );
	?>
	<input style='direction:ltr;' type='text' name='tbot_settings[tbot_text_admin]' value='<?php echo $options['tbot_text_admin']; ?>'>
	<p><?php echo __( 'every user that send this password to the bot will be known as admin. whenever an admin user sends message to the bot, this message will be sent as a newsletter.', 'tbot' ); ?></p>
	<p><?php echo __( 'admins can send text messages and photos directly from their phones to all subscribers.', 'tbot' ); ?></p>
	<?php

}
function tbot_select_admin_update_render(  ) { 

	$options = get_option( 'tbot_settings' );
	?>
	<select name='tbot_settings[tbot_select_admin_update]'>
		<option value='subs' <?php selected( $options['tbot_select_admin_update'], 'subs' ); ?>><?php printf(__( 'New Subscriber', 'tbot' )); ?></option>
		<option value='unsubs' <?php selected( $options['tbot_select_admin_update'], 'unsubs' ); ?>><?php printf(__( 'New Unsubscriber', 'tbot' )); ?></option>
		<option value='both' <?php selected( $options['tbot_select_admin_update'], 'both' ); ?>><?php printf(__( 'Both', 'tbot' )); ?></option>
		<option value='none' <?php selected( $options['tbot_select_admin_update'], 'both' ); ?>><?php printf(__( 'Disable', 'tbot' )); ?></option>
	</select>
	<p><?php echo __( 'Admin users can receive alerts for new subscribers ans un subscription.', 'tbot' ); ?></p>
<?php
}
function tbot_post_types_render( $type ) { 
$type = $type['type'];
	$options = get_option( 'tbot_settings' );
	?>
	<input type='checkbox' name='tbot_settings[tbot_<?php echo $type; ?>]' <?php checked( $options['tbot_'.$type], $type ); ?> value='<?php echo $type; ?>'>
	<label><?php echo $type; ?></label>
	<?php
}

function tbot_settings_section_callback(  ) { 

	echo __( 'Before updating this settings please read the Documents carefully', 'tbot' );

}


function telegram_api_options_page(  ) { 

	?>
	<form action='options.php' method='post'>
		<style>
		a.websimabanner {
			right:5%;left:auto;position: absolute;
		}
		body.rtl a.websimabanner {
			left: 5%;right:auto
		}
		</style>
		<h2><?php printf(__( 'Telegram API', 'tbot' )); ?></h2>
		<a class="websimabanner" href="http://websima.com/teletter" title="websima teletter"><img width="281" height="364" src="<?php echo plugins_url( 'teletter.png', __FILE__ ); ?>" alt="وبسیما تلتر"/></a>
		<?php
		settings_fields( 'pluginPage' );
		do_settings_sections( 'pluginPage' );
		submit_button();
		?>
		
	</form>
	<?php

}

?>