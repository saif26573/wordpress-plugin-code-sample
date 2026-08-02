<?php

namespace SaifKhan\VendorInventory\Support;

use WP_Error;

defined( 'ABSPATH' ) || exit;

final class Validation {
	private const STATUSES = array( 'active', 'listed', 'sold', 'archived' );

	public function inventory_payload( array $input ) {
		$title = sanitize_text_field( $input['title'] ?? '' );
		if ( '' === $title ) {
			return new WP_Error( 'vim_title_required', 'A title is required.', array( 'status' => 422 ) );
		}

		$status = sanitize_key( $input['status'] ?? 'active' );
		if ( ! in_array( $status, self::STATUSES, true ) ) {
			return new WP_Error( 'vim_invalid_status', 'The supplied status is not valid.', array( 'status' => 422 ) );
		}

		$cost  = max( 0, (float) ( $input['cost'] ?? 0 ) );
		$value = max( 0, (float) ( $input['estimated_value'] ?? 0 ) );

		return array(
			'title'           => $title,
			'sku'             => sanitize_text_field( $input['sku'] ?? '' ),
			'status'          => $status,
			'cost'            => number_format( $cost, 2, '.', '' ),
			'estimated_value' => number_format( $value, 2, '.', '' ),
		);
	}
}

