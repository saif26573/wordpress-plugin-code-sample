<?php

namespace SaifKhan\VendorInventory;

use SaifKhan\VendorInventory\Domain\InventoryRepository;
use SaifKhan\VendorInventory\Rest\InventoryController;
use SaifKhan\VendorInventory\Support\Authorization;
use SaifKhan\VendorInventory\Support\Validation;

defined( 'ABSPATH' ) || exit;

final class Plugin {
	public function register(): void {
		add_action( 'rest_api_init', array( $this, 'register_rest_routes' ) );
	}

	public function register_rest_routes(): void {
		$repository    = new InventoryRepository();
		$authorization = new Authorization( $repository );
		$validation    = new Validation();
		$controller    = new InventoryController( $repository, $authorization, $validation );

		$controller->register_routes();
	}
}

