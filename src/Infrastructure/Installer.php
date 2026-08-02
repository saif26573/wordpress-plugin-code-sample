<?php

namespace SaifKhan\VendorInventory\Infrastructure;

defined( 'ABSPATH' ) || exit;

final class Installer {
	public static function table_name(): string {
		global $wpdb;

		return $wpdb->prefix . 'vim_inventory';
	}

	public static function activate(): void {
		global $wpdb;

		$table   = self::table_name();
		$collate = $wpdb->get_charset_collate();
		$sql     = "CREATE TABLE {$table} (
			id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
			owner_id bigint(20) unsigned NOT NULL,
			title varchar(255) NOT NULL,
			sku varchar(100) NOT NULL DEFAULT '',
			status varchar(20) NOT NULL DEFAULT 'active',
			cost decimal(15,2) NOT NULL DEFAULT 0.00,
			estimated_value decimal(15,2) NOT NULL DEFAULT 0.00,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (id),
			KEY owner_status (owner_id, status),
			KEY sku (sku)
		) {$collate};";

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		dbDelta( $sql );

		update_option( 'vim_sample_db_version', VIM_SAMPLE_VERSION, false );
	}
}

