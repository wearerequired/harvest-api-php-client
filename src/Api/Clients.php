<?php
/**
 * Clients class.
 */

namespace Required\Harvest\Api;

use DateTime;

/**
 * API client for clients endpoint.
 *
 * @link https://help.getharvest.com/api-v2/clients-api/clients/clients/
 */
class Clients extends AbstractApi implements ClientsInterface {


	/**
	 * Retrieves a list of clients.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of clients. Default empty array.
	 *
	 *     @type bool            $is_active      Pass `true` to only return active clients and `false` to return
	 *                                           inactive clients.
	 *     @type DateTime|string $updated_since  Only return clients that have been updated since the given
	 *                                           date and time.
	 * }
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function all( array $parameters = [] ) {
		if ( isset( $parameters['updated_since'] ) && $parameters['updated_since'] instanceof DateTime ) {
			$parameters['updated_since'] = $parameters['updated_since']->format( DateTime::ATOM );
		}

		if ( isset( $parameters['is_active'] ) ) {
			$parameters['is_active'] = filter_var( $parameters['is_active'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
		}

		$result = $this->get( '/clients', $parameters );
		if ( ! isset( $result['clients'] ) || ! \is_array( $result['clients'] ) ) {
			throw new \Required\Harvest\Exception\RuntimeException( 'Unexpected result.' );
		}

		return $result['clients'];
	}

	/**
	 * Retrieves the client with the given ID.
	 *
	 * @param int $clientId The ID of the client.
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function show( int $clientId ) {
		return $this->get( '/clients/' . rawurlencode( $clientId ) );
	}

	/**
	 * Creates a new client object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new client object.
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function create( array $parameters ) {
		if ( ! isset( $parameters['name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'name' );
		}

		if ( ! \is_string( $parameters['name'] ) || empty( trim( $parameters['name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "name" parameter must be a non-empty string.' );
		}

		return $this->post( '/clients', $parameters );
	}

	/**
	 * Updates the specific client by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $clientId The ID of the client.
	 * @param array $parameters
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function update( int $clientId, array $parameters ) {
		return $this->patch( '/clients/' . rawurlencode( $clientId ), $parameters );
	}

	/**
	 * Deletes a client.
	 *
	 * Deleting a client is only possible if it has no projects, invoices, or estimates associated with it.
	 *
	 * @param int $clientId The ID of the client.
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function remove( int $clientId ) {
		return $this->delete( '/clients/' . rawurlencode( $clientId ) );
	}
}
