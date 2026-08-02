<?php
/**
 * Plugin Name: Vendor Inventory Manager — Code Sample
 * Description: Sanitized example of a modular WordPress inventory plugin.
 * Version: 1.0.0
 * Author: Saif Khan
 * Requires PHP: 7.4
 */

defined( 'ABSPATH' ) || exit;

define( 'VIM_SAMPLE_VERSION', '1.0.0' );
define( 'VIM_SAMPLE_FILE', __FILE__ );
define( 'VIM_SAMPLE_PATH', plugin_dir_path( __FILE__ ) );

spl_autoload_register(
	static function ( $class ) {
		$prefix = 'SaifKhan\\VendorInventory\\';

		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}

		$relative = substr( $class, strlen( $prefix ) );
		$file     = VIM_SAMPLE_PATH . 'src/' . str_replace( '\\', '/', $relative ) . '.php';

		if ( is_readable( $file ) ) {
			require_once $file;
		}
	}
);

register_activation_hook(
	__FILE__,
	array( 'SaifKhan\\VendorInventory\\Infrastructure\\Installer', 'activate' )
);

add_action(
	'plugins_loaded',
	static function () {
		$plugin = new SaifKhan\VendorInventory\Plugin();
		$plugin->register();
	}
);

