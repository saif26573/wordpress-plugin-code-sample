<?php

namespace SaifKhan\VendorInventory\Support;

use SaifKhan\VendorInventory\Domain\InventoryRepository;

defined( 'ABSPATH' ) || exit;

final class Authorization {
	private InventoryRepository $repository;

	public function __construct( InventoryRepository $repository ) {
		$this->repository = $repository;
	}

	public function can_access(): bool {
		return is_user_logged_in() && current_user_can( 'read' );
	}

	public function can_modify( int $record_id ): bool {
		if ( current_user_can( 'manage_options' ) ) {
			return true;
		}

		return null !== $this->repository->find_for_owner( $record_id, get_current_user_id() );
	}
}

