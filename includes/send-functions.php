<?php
function tbotFullPath($url)
{
$current_path = getcwd(); // get the current path to where the file is located
$folder = explode("/", $current_path); // divide the path in parts (aka folders)
$blog = $folder[8]; // the blog's folder is the number 8 on the path
// $root = path without the blog installation folder.
$root = realpath($_SERVER["DOCUMENT_ROOT"]);
$url = str_replace(get_bloginfo('url') , '', $url);
return $root.$dir.$url;

}
function teletter_sendphoto($chat_id, $caption, $photo)
{

	$options = get_option( 'tbot_settings' );
    $token = $options['tbot_text_token'];
	$photo = tbotFullPath($photo);
	$finfo = finfo_open(FILEINFO_MIME_TYPE);
	$mime_type = finfo_file($finfo, $photo);
    $fields = array(
        'chat_id' => urlencode($chat_id),
         // make sure you do NOT forget @ sign
         'photo' =>
          '@'            . $photo
          . ';filename=' . $photo
          . ';type='     . $mime_type,
        'caption' => $caption
    );

    $url = 'https://api.telegram.org/bot'.$token.'/sendPhoto';

    //  open connection
    $ch = curl_init();
    //  set the url
    curl_setopt($ch, CURLOPT_URL, $url);
    //  number of POST vars
    curl_setopt($ch, CURLOPT_POST, count($fields));
    //  POST data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    //  To display result of curl
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, false);
    //  execute post
    $result = curl_exec($ch);
    //  close connection
    curl_close($ch);
}

function sendmessagebot ($user_id,$message) {
	$options = get_option( 'tbot_settings' );
    $token = $options['tbot_text_token'];
	if ($token) {
	$url = 'https://api.telegram.org/bot'.$token.'/sendMessage';
	$data = array('chat_id' => $user_id,'text' => $message);
		$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
		)
	);

	$context  = stream_context_create($options);
	$update = @file_get_contents($url, false, $context);
	//end send message
	}
}
function sendphotobot ($user_id,$photo) {
	$options = get_option( 'tbot_settings' );
    $token = $options['tbot_text_token'];
	if ($token) {
	$url = 'https://api.telegram.org/bot'.$token.'/sendPhoto';
	$data = array('chat_id' => $user_id,'photo' => $photo);
		$options = array(
        'http' => array(
        'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
        'method'  => 'POST',
        'content' => http_build_query($data),
		)
	);

	$context  = stream_context_create($options);
	$update = @file_get_contents($url, false, $context);
	//end send message
	}
}
function sendnewsbot ($message,$offset,$limit) {
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

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		//Message to every user
		$chat_id = get_the_title();
		sendmessagebot ($chat_id,$message);
   }
} 

wp_reset_postdata();
}
function sendphotonewsbot ($photo,$offset,$limit) {
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

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		//Message to every user
		$chat_id = get_the_title();
		sendphotobot ($chat_id,$photo);
   }
} 

wp_reset_postdata();
}
function sendadminmessagebot ($message) {
	// Send a message to Admin to alert for subscription changes
	$options = get_option( 'tbot_settings' );
	$token = $options['tbot_text_token'];
	// Get All Admins
	$args = array (
	'post_type'              => array( 'subscriber' ),
	'pagination'             => false,
	'posts_per_page'         => $limit,
	'meta_key' => 'isadmin',
	'meta_value' => 'yes',
	'meta_compare' => '==',
	);	
// The Query
$query = new WP_Query( $args );

// The Loop
if ( $query->have_posts() ) {
	while ( $query->have_posts() ) {
		$query->the_post();
		//Message to every user
		$chat_id = get_the_title();
		sendmessagebot ($chat_id,$message);
   }
} 

wp_reset_postdata();
}
?>