<?php

namespace Required\Harvest\Api;

/**
 * API client for clients endpoint.
 *
 * @link https://help.getharvest.com/api-v2/clients-api/clients/clients/
 */
interface ClientsInterface {

	/**
	 * Retrieves a list of clients.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of clients. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active clients and `false` to return
	 *                                           inactive clients.
	 *     @type DateTime|string $updated_since Only return clients that have been updated since the given
	 *                                           date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the client with the given ID.
	 *
	 * @param int $clientId The ID of the client.
	 * @return array|string
	 */
	public function show( int $clientId );

	/**
	 * Creates a new client object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new client object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific client by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $clientId The ID of the client.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $clientId, array $parameters );

	/**
	 * Deletes a client.
	 *
	 * Deleting a client is only possible if it has no projects, invoices, or estimates associated with it.
	 *
	 * @param int $clientId The ID of the client.
	 * @return array|string
	 */
	public function remove( int $clientId );
}
