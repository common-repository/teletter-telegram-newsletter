<?php
$options = get_option( 'tbot_settings' );
$token = $options['tbot_text_token'];
$offset = get_site_option( 'lastupdateid' );
$limit = $options['tbot_select_limit'];
$welcome = $options['tbot_text_newsubscriber'];
$unsubscribe = $options['tbot_text_unsubscribe'];
$unsubcommand = $options['tbot_text_unsubscribe_command'];
$adminpass = $options['tbot_text_admin'];
$adminupdate = $options['tbot_select_admin_update'];
if($unsubcommand == '') {$unsubcommand == '/remove';}
if($welcome == '') {$welcome = 'Successfully Subscribed';}
if($unsubscribe == '') {$unsubscribe = 'Successfully Un Subscribed';}
$offset = $offset+1;
$url = 'https://api.telegram.org/bot'.$token.'/getUpdates';
if ($offset == 1) {
	if ($limit == "nolimit") {
		$data = array();
	} else {
		$data = array('limit' => $limit);
	}
	
} else {
	if ($limit == "nolimit") {
		$data = array('offset' => $offset);
	} else {
		$data = array('offset' => $offset,'limit' => $limit);
	}
	
}
$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
    )
);

$context  = stream_context_create($options);
$update = @file_get_contents($url, false, $context);
$results = json_decode($update, true);

$status = $results['ok'];
if($status == 1) {
	$newupdates = $results['result'];
	$number = count($newupdates);
	printf(__('%s New Updates Received...<br/>', 'tbot'), $number);
	$i = 0;
	$j = 0;
foreach ($newupdates as $update) {

    //print_r ($value);
	$update_id = $update['update_id'];
	$from = $update['message']['from'];
	$user_id = $from['id'];
	$first_name = $from['first_name'];
	$last_name = $from['last_name'];
	$user_name = $from['username'];
	$date = $update['message']['date'];
	$text = $update['message']['text'];
	$photo = $update['message']['photo'];
		
	$post_date = date_i18n( 'Y-m-d H:i:s', $date, true );	
	if ($text == '/start') {
		$checker = get_page_by_title( $user_id,  ARRAY_A, 'subscriber' );
		if (!$checker){
		teletter_log('new subscriber', $update_id ,$user_id, $text);
	// Create post object
		$my_post = array(
		'post_type'     => 'subscriber',
		'post_title'    => $user_id,
		'post_status'   => 'publish',
		'post_date_gmt' => $post_date,
		);
	// Insert the post into the database
		$post_id = wp_insert_post( $my_post, $wp_error );
		update_post_meta ($post_id,'first_name',$first_name);
		update_post_meta ($post_id,'last_name',$last_name);
		update_post_meta ($post_id,'username',$user_name);
		update_post_meta ($post_id,'activity','active');
		$j++;
	// Send a message to user to know that subscriptions is activated
	sendmessagebot ($user_id,$welcome);
	teletter_log(__('New Subscriber', 'tbot'), $update_id ,$user_id, $text);
	if ($adminupdate == 'both' || $adminupdate == 'subs') {
	sendadminmessagebot (__('New Subscriber', 'tbot'));
	}
		}
	} elseif ($text == $unsubcommand) {
		$checker = get_page_by_title( $user_id,  OBJECT, 'subscriber' );
		if ($checker){
	// make user de active
		update_post_meta ($checker->ID,'activity','deactive');
		teletter_log('new unsubscriber', $update_id ,$user_id, $text);
	// Send a message to user to know that unsubscriptions is activated
	sendmessagebot ($user_id,$unsubscribe);
	if ($adminupdate == 'both' || $adminupdate == 'unsubs') {
	sendadminmessagebot (__('New Un Subscription', 'tbot'));
	}
		}
	} elseif ($text == $adminpass ) {
		if (!$adminpass == '') {
				$checker = get_page_by_title( $user_id,  OBJECT, 'subscriber' );
			if ($checker){
			// make user de active
			update_post_meta ($checker->ID,'isadmin','yes');
			teletter_log(__('New Admin', 'tbot'), $update_id ,$user_id, $text);
			// Send a message to user to know that Admin Access is activated
			sendmessagebot ($user_id,__('You Are the Boss!', 'tbot'));
			}	
		}

	} else {
		$checker = get_page_by_title( $user_id,  OBJECT, 'subscriber' );
		if ($checker){
	// check if is user admin
		$isadmin = get_post_meta ($checker->ID,'isadmin',true);
		if ($isadmin == 'yes') {
				$args = array (
				'post_type'              => array( 'subscriber' ),
				'pagination'             => false,
				'posts_per_page'         => '-1',
				);
				$query = new WP_Query( $args );
				$total = $query->post_count;
				$options = get_option( 'tbot_settings' );
				$limit = $options['tbot_select_sendlimit'];
				if ($limit == 'nolimit') {$limit = $total;}
				$count = intval($total / $limit);
				for ($i=0;$i<=$count;$i++) {
					$offset = $i * $limit;
					if ($text) {
						sendnewsbot ($text,$offset,$limit);
						teletter_log(__('Admin Sent Text', 'tbot'), $update_id ,$user_id, $text);

					} elseif ($photo) {
						sendphotonewsbot ($photo[0]['file_id'],$offset,$limit);
						teletter_log(__('Admin Sent Photo', 'tbot'), $update_id ,$user_id, 'photo');
						
					}
					
				}			
		} else {
			teletter_log(__('No Response', 'tbot'), $update_id ,$user_id, $text);
		}
	
		}
	} 
	$i++;
		update_site_option( 'lastupdateid', $update_id ); //Grab the last update for cronjob
}
printf(__('%s New Subscribers Added to your List.<br />', 'tbot'), $j);
} else {
	printf(__('There is Some Errors, No Updates Received from Telegram API!. Please Check Your Telegram API Settings.<br />', 'tbot'));
}
?>