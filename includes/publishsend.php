<?php
function sendmessage_publish($post_ID,$offset,$limit) {

	   $type = get_post_meta ($post_ID,'tbot_select',true);
	   $custommessage = get_post_meta ($post_ID,'tbot_text',true);
	   $shorturl =  wp_get_shortlink($post_ID);
	   $url = get_permalink($post_ID); 
	   $image =  get_post_meta ($post_ID,'tbot_image',true);
	   if ($type == 'url'){
		   $message = $url;
	   } elseif ($type == 'shorturl'){
		   $message = $shorturl;
	   } elseif ($type == 'customurl'){
		   $message = $custommessage.' '.$url;
	   } elseif ($type == 'shortcustomurl'){
		   $message = $custommessage.' '.$shorturl;
	   } elseif ($type == 'customtext') {
		   $message = $custommessage;
	   }
	// Send a message to user to know that subscriptions is activated
	$options = get_option( 'tbot_settings' );
	$token = $options['tbot_text_token'];
	$users = $options['tbot_select_users'];
	// Get All Subscribers
	if ($users == 'all') {
	$args = array (
	'post_type'              => array( 'subscriber' ),
	'pagination'             => false,
	'posts_per_page'         => $limit,
	'offset'         => $offset,
	);
	} else if ($users == 'active') {
	$args = array (
	'post_type'              => array( 'subscriber' ),
	'pagination'             => false,
	'posts_per_page'         => $limit,
	'meta_key' => 'activity',
	'meta_value' => 'active',
	'meta_compare' => '==',
	'offset'         => $offset,
	);	
	} else {
	$args = array (
	'post_type'              => array( 'subscriber' ),
	'pagination'             => false,
	'posts_per_page'         => $limit,
	'offset'         => $offset,
	);
	}


	// The Query
	$query = new WP_Query( $args );
if ($image) {
	$logger =  $message.' image:'.$image;
	teletter_log('Send Photo to Users', '-' ,'from admin panel',$logger);
	// The Loop
	if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		//Message to every user
		$chat_id = get_the_title();
		teletter_sendphoto($chat_id, $message, $image);
   }
} 
} else {
	teletter_log('Send Messages to Users', '-' ,'from admin panel', $message);
	// The Loop
	if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		//Message to every user
		$chat_id = get_the_title();
		sendmessagebot ($chat_id,$message);
   }
} 
}


// Restore original Post Data
wp_reset_postdata();
 //  }
}
?>