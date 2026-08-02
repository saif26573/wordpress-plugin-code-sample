<?php

namespace SaifKhan\VendorInventory\Domain;

use SaifKhan\VendorInventory\Infrastructure\Installer;

defined( 'ABSPATH' ) || exit;

final class InventoryRepository {
	public function find_for_owner( int $id, int $owner_id ): ?array {
		global $wpdb;

		$sql = $wpdb->prepare(
			'SELECT * FROM ' . Installer::table_name() . ' WHERE id = %d AND owner_id = %d LIMIT 1',
			$id,
			$owner_id
		);
		$row = $wpdb->get_row( $sql, ARRAY_A );

		return $row ? $this->cast( $row ) : null;
	}

	public function list_for_owner( int $owner_id, string $status = '' ): array {
		global $wpdb;

		$table = Installer::table_name();
		if ( '' !== $status ) {
			$sql = $wpdb->prepare(
				"SELECT * FROM {$table} WHERE owner_id = %d AND status = %s ORDER BY id DESC",
				$owner_id,
				$status
			);
		} else {
			$sql = $wpdb->prepare(
				"SELECT * FROM {$table} WHERE owner_id = %d ORDER BY id DESC",
				$owner_id
			);
		}

		$rows = $wpdb->get_results( $sql, ARRAY_A );

		return array_map( array( $this, 'cast' ), $rows ?: array() );
	}

	public function create( int $owner_id, array $data ): int {
		global $wpdb;

		$now = current_time( 'mysql', true );
		$wpdb->insert(
			Installer::table_name(),
			array_merge( $data, array( 'owner_id' => $owner_id, 'created_at' => $now, 'updated_at' => $now ) ),
			array( '%s', '%s', '%s', '%f', '%f', '%d', '%s', '%s' )
		);

		return (int) $wpdb->insert_id;
	}

	public function update( int $id, int $owner_id, array $data ): bool {
		global $wpdb;

		$data['updated_at'] = current_time( 'mysql', true );
		$result = $wpdb->update(
			Installer::table_name(),
			$data,
			array( 'id' => $id, 'owner_id' => $owner_id )
		);

		return false !== $result;
	}

	public function delete( int $id, int $owner_id ): bool {
		global $wpdb;

		return 1 === $wpdb->delete(
			Installer::table_name(),
			array( 'id' => $id, 'owner_id' => $owner_id ),
			array( '%d', '%d' )
		);
	}

	private function cast( array $row ): array {
		$row['id']              = (int) $row['id'];
		$row['owner_id']        = (int) $row['owner_id'];
		$row['cost']            = (float) $row['cost'];
		$row['estimated_value'] = (float) $row['estimated_value'];

		return $row;
	}
}

