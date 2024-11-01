<?php 

/**
*  Activation Class 
**/
if ( ! class_exists( 'UM_PostWallInstallCheck' ) ) {
  class UM_PostWallInstallCheck {
		static function install() {
			/**
			* Check if WooCommerce & Cubepoints are active
			**/
			if ( !in_array( 'um-activity/um-activity.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) &&
					!in_array( 'ultimate-member/um-init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
				
				// Deactivate the plugin
				deactivate_plugins(__FILE__);
				
				// Throw an error in the wordpress admin console
				$error_message = __('This plugin requires <a href="https://wordpress.org/plugins/ultimate-member/">Ultimate Member</a> &amp; <a href="https://ultimatemember.com/extensions/social-activity/">	
Ultimate Member - Social Activity</a> plugins to be active!', 'woocommerce');
				die($error_message);
				
			}
		}
	}
}




?>