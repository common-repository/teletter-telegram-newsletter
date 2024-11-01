<?php
add_action( 'add_meta_boxes', 'tbot_meta_box_add' );
function tbot_meta_box_add()
{
$options = get_option( 'tbot_settings' );
$args = array('public'   => true);
$output = 'names'; // names or objects, note names is the default
$operator = 'and'; // 'and' or 'or'
$post_types = get_post_types( $args, $output, $operator ); 
foreach ( $post_types  as $post_type ) {
	if ($post_type == 'attachment' || $post_type == 'subscriber') {} else {
		$option = $options['tbot_'.$post_type];
		if ($option == $post_type) {
		add_meta_box( 'tbot-meta-box', __('Telegram Newsletter', 'tbot'), 'tbot_meta_box_cb', $post_type, 'side', 'high' );
		}
	}
}
}

function tbot_meta_box_cb( $post )
{
	$values = get_post_custom( $post->ID );
	$text = isset( $values['tbot_text'] ) ? esc_attr( $values['tbot_text'][0] ) : '';
	$image = isset( $values['tbot_image'] ) ? esc_attr( $values['tbot_image'][0] ) : '';
	$selected = isset( $values['tbot_select'] ) ? esc_attr( $values['tbot_select'][0] ) : '';
	$check = isset( $values['tbot_check'] ) ? esc_attr( $values['tbot_check'][0] ) : '';
	wp_nonce_field( 'tbot_nonce', 'meta_box_nonce' );
	?>
	<p>
		<input type="checkbox" name="tbot_check" id="tbot_check" <?php checked( $check, 'on' ); ?> />
		<label for="tbot_check"><?php printf(__( 'Send to All Subscribers.', 'tbot' )); ?></label>
	</p>
	<p>
		<label for="tbot_select"><?php printf(__( 'Message Text', 'tbot' )); ?></label>
		<select name="tbot_select" id="tbot_select">
			<option value="url" <?php selected( $selected, 'url' ); ?>><?php printf(__( 'URL', 'tbot' )); ?></option>
			<option value="shorturl" <?php selected( $selected, 'shorturl' ); ?>><?php printf(__( 'Short URL', 'tbot' )); ?></option>
			<option value="customurl" <?php selected( $selected, 'customurl' ); ?>><?php printf(__( 'URL and Custom Message', 'tbot' )); ?></option>
			<option value="shortcustomurl" <?php selected( $selected, 'shortcustomurl' ); ?>><?php printf(__( 'Short URL and Custom Message', 'tbot' )); ?></option>
			<option value="customtext" <?php selected( $selected, 'customtext' ); ?>><?php printf(__( 'Custom Message', 'tbot' )); ?></option>
		</select>
	</p>

	<p>
		<label for="tbot_text" style="display: block;"><?php printf(__( 'Custom Message', 'tbot' )); ?></label>
		<textarea style="width: 99%;"rows="3" name="tbot_text" id="tbot_text"><?php echo $text; ?></textarea>
	</p>
	<p>
		<label for="tbot_image" style="display: block;"><?php printf(__( 'Custom Image', 'tbot' )); ?></label>
		<textarea style="width: 99%;"rows="3" name="tbot_image" id="tbot_image"><?php echo $image; ?></textarea>
		<span><?php printf(__( 'Please Enter Full Image URL on your site, Leave empty to not send images', 'tbot' )); ?></span>
	</p>
	<?php	
}


add_action( 'save_post', 'tbot_meta_box_save' );
function tbot_meta_box_save( $post_id )
{
	// Bail if we're doing an auto save
	if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
	
	// if our nonce isn't there, or we can't verify it, bail
	if( !isset( $_POST['meta_box_nonce'] ) || !wp_verify_nonce( $_POST['meta_box_nonce'], 'tbot_nonce' ) ) return;
	
	// if our current user can't edit this post, bail
	if( !current_user_can( 'edit_post' ) ) return;
	
	// now we can actually save the data
	$allowed = array( 
		'a' => array( // on allow a tags
			'href' => array() // and those anchords can only have href attribute
		)
	);
	
	// Probably a good idea to make sure your data is set
	if( isset( $_POST['tbot_text'] ) )
		update_post_meta( $post_id, 'tbot_text', wp_kses( $_POST['tbot_text'], $allowed ) );
	if( isset( $_POST['tbot_image'] ) )
		update_post_meta( $post_id, 'tbot_image', wp_kses( $_POST['tbot_image'], $allowed ) );
		
	if( isset( $_POST['tbot_select'] ) )
		update_post_meta( $post_id, 'tbot_select', esc_attr( $_POST['tbot_select'] ) );
		
	// This is purely my personal preference for saving checkboxes
	$chk = ( isset( $_POST['tbot_check'] ) && $_POST['tbot_check'] ) ? 'on' : 'off';
	if ($chk == 'on'){
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
	sendmessage_publish ($post_id, $offset,$limit);
	}
	}

}
//add_action( 'save_post', 'sendmessage_publish' );
?>