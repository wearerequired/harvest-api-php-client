<?php

namespace Required\Harvest\Api;

/**
 * API client for contacts endpoint.
 *
 * @link https://help.getharvest.com/api-v2/clients-api/clients/contacts/
 */
interface ContactsInterface {

	/**
	 * Retrieves a list of contacts.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of contacts. Default empty array.
	 *
	 *     @type int $client_id                 Only return contacts belonging to the client with the given ID.
	 *     @type DateTime|string $updated_since Only return contacts that have been updated since the given
	 *                                           date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the contact with the given ID.
	 *
	 * @param int $contactId The ID of the contact.
	 * @return array|string
	 */
	public function show( int $contactId );

	/**
	 * Creates a new contact object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new contact object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific contact by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $contactId The ID of the contact.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $contactId, array $parameters );

	/**
	 * Deletes a contact.
	 *
	 * @param int $contactId The ID of the contact.
	 * @return array|string
	 */
	public function remove( int $contactId );
}
