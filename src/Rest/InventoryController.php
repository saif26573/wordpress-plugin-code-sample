<?php

namespace SaifKhan\VendorInventory\Rest;

use SaifKhan\VendorInventory\Domain\InventoryRepository;
use SaifKhan\VendorInventory\Support\Authorization;
use SaifKhan\VendorInventory\Support\Validation;
use WP_Error;
use WP_REST_Request;
use WP_REST_Response;
use WP_REST_Server;

defined( 'ABSPATH' ) || exit;

final class InventoryController {
	private InventoryRepository $repository;
	private Authorization $authorization;
	private Validation $validation;

	public function __construct( InventoryRepository $repository, Authorization $authorization, Validation $validation ) {
		$this->repository    = $repository;
		$this->authorization = $authorization;
		$this->validation    = $validation;
	}

	public function register_routes(): void {
		register_rest_route(
			'vim/v1',
			'/inventory',
			array(
				array(
					'methods'             => WP_REST_Server::READABLE,
					'callback'            => array( $this, 'index' ),
					'permission_callback' => array( $this->authorization, 'can_access' ),
				),
				array(
					'methods'             => WP_REST_Server::CREATABLE,
					'callback'            => array( $this, 'create' ),
					'permission_callback' => array( $this->authorization, 'can_access' ),
				),
			)
		);

		register_rest_route(
			'vim/v1',
			'/inventory/(?P<id>\d+)',
			array(
				array(
					'methods'             => WP_REST_Server::EDITABLE,
					'callback'            => array( $this, 'update' ),
					'permission_callback' => array( $this, 'can_modify' ),
				),
				array(
					'methods'             => WP_REST_Server::DELETABLE,
					'callback'            => array( $this, 'delete' ),
					'permission_callback' => array( $this, 'can_modify' ),
				),
			)
		);
	}

	public function index( WP_REST_Request $request ): WP_REST_Response {
		$status = sanitize_key( (string) $request->get_param( 'status' ) );
		$items  = $this->repository->list_for_owner( get_current_user_id(), $status );

		return new WP_REST_Response( array( 'items' => $items ), 200 );
	}

	public function create( WP_REST_Request $request ) {
		$data = $this->validation->inventory_payload( (array) $request->get_json_params() );
		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$id = $this->repository->create( get_current_user_id(), $data );
		if ( ! $id ) {
			return new WP_Error( 'vim_create_failed', 'The record could not be created.', array( 'status' => 500 ) );
		}

		return new WP_REST_Response( $this->repository->find_for_owner( $id, get_current_user_id() ), 201 );
	}

	public function update( WP_REST_Request $request ) {
		$id   = absint( $request['id'] );
		$data = $this->validation->inventory_payload( (array) $request->get_json_params() );
		if ( is_wp_error( $data ) ) {
			return $data;
		}

		$this->repository->update( $id, get_current_user_id(), $data );

		return new WP_REST_Response( $this->repository->find_for_owner( $id, get_current_user_id() ), 200 );
	}

	public function delete( WP_REST_Request $request ): WP_REST_Response {
		$deleted = $this->repository->delete( absint( $request['id'] ), get_current_user_id() );

		return new WP_REST_Response( array( 'deleted' => $deleted ), $deleted ? 200 : 404 );
	}

	public function can_modify( WP_REST_Request $request ): bool {
		return $this->authorization->can_modify( absint( $request['id'] ) );
	}
}

