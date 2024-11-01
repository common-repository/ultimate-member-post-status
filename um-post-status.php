<?php
/*
Plugin Name: Ultimate Member - Post Status
Plugin URI: http://www.umplugins.com/
Description: Adds a shortcode to Ultimate Member that creates a button for a status update modal like Twitter.  Shortcode: [post_wall_modal label="Post Wall Message"].
Author: UMPlugins
Version: 1.0.1
Author URI: http://www.umplugins.com/
*/


/***
***	@Check compatibility
***/

include_once dirname( __FILE__ ) . '/core/class-install_check.php';
register_activation_hook( __FILE__, array('UM_PostWallInstallCheck', 'install') );

/***
***	@Include CSS/JS file
***/

function um_post_status_css_js(){
    // Register the script for Post Wall Plugin
	wp_register_style('um_post_status_css', plugins_url('assets/css/um-post-status.css',__FILE__ ));
	wp_enqueue_style('um_post_status_css');

	// Register the script for Post Wall Plugin
    wp_register_script( 'um_post_status', plugins_url( 'assets/js/um-post-status.js', __FILE__ ) );
    wp_enqueue_script( 'um_post_status' );
	
	//override um-activity.js from um-activity plugin
	wp_dequeue_script( 'um_activity' );
	wp_register_script( 'um_activity_post_status', plugins_url( 'assets/js/override/um-activity.js', __FILE__ ) );
    wp_enqueue_script( 'um_activity_post_status' );
}
add_action( 'wp_enqueue_scripts', 'um_post_status_css_js', 5 );


/***
***	@Add Shortcode
***/
function um_post_wall_modal_popup( $args = array() ) {

	$defaults = array(
			'label' => 'Post Wall Messge',
		);
	$args = wp_parse_args( $args, $defaults );

	extract( $args );
	ob_start();
	echo '<div class="um-post-wall">';
	echo '<a class="um-post-wall-btn um-button" href="#"><span>'.$label.'</span></a>';
	echo '</div>';
	$output = ob_get_contents();
	ob_end_clean();

	return $output;
}
add_shortcode( 'post_wall_modal', 'um_post_wall_modal_popup' );

/***
***	@Coming from shortcode button
***/

add_action('wp_ajax_nopriv_um_post_wall_modal', 'um_post_wall_modal');
add_action('wp_ajax_um_post_wall_modal', 'um_post_wall_modal');
function um_post_wall_modal(){	
	show_post_wall();
	exit();
}

/***
***	@UM Post Wall HTML
***/

function ultimatemember_post_wall( $args = array() ) {

	global $ultimatemember, $um_activity;
	$activity_shortcode = new UM_Activity_Shortcode();

	$defaults = array(
		'user_id' => get_current_user_id(),
		'hashtag' => ( isset( $_GET['hashtag'] ) ) ? esc_attr( wp_strip_all_tags( $_GET['hashtag'] ) ) : '',
		'wall_post' =>  ( isset( $_GET['wall_post'] ) ) ? absint( $_GET['wall_post'] ) : '',
		'user_wall' => 1
	);

	$args = wp_parse_args( $args, $defaults );
	extract( $args );
	$activity_shortcode->args = $args;

	ob_start();
	if ( $um_activity->api->can_write() && $wall_post == 0 && !$hashtag ) {
		$activity_shortcode->load_template('new');
	}
	$output = ob_get_contents();
	ob_end_clean();
	
	return $output;
}
add_shortcode( 'post_wall', 'ultimatemember_post_wall' );

/***
***	@show post on wall
***/
function show_post_wall() {
	$can_view = apply_filters('um_wall_can_view', -1, um_profile_id() );
	if ( $can_view == -1 ) { ?>
		<div class="um-message-header um-popup-header um-post-wall">
            <div class="um-message-header-left"></div>
            <div class="um-message-header-right" style="">
                <a href="#" class="um-message-hide um-tip-e" title="<?php _e('Close Wall','um-messaging'); ?>"><i class="um-icon-close"></i></a>
            </div>
        </div>
        <script type="text/javascript">
			jQuery(document).ready(function(e) {
                UM_wall_img_upload();
            });
        </script>
	<?php echo do_shortcode('[post_wall user_id='.um_profile_id().']'); ?>
	<div class="um-message-footer um-popup-footer um-post-wall-modal-footer">
    	<div class="um-wall-msgs"></div>
	</div>
	<?php
	} else {
		echo '<div class="um-profile-note"><span><i class="um-faicon-lock"></i>' . $can_view . '</span></div>';
	}
}


