<?php

defined( 'WP_UNINSTALL_PLUGIN' ) || exit;

// Production plugins should preserve data by default. Explicit opt-in is required.
if ( ! defined( 'VIM_SAMPLE_REMOVE_DATA' ) || true !== VIM_SAMPLE_REMOVE_DATA ) {
	return;
}

global $wpdb;
$wpdb->query( 'DROP TABLE IF EXISTS ' . $wpdb->prefix . 'vim_inventory' );
delete_option( 'vim_sample_db_version' );

