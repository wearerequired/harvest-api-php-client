<?php

namespace Required\Harvest\Api;

use Required\Harvest\Exception\InvalidArgumentException;
use Required\Harvest\Exception\MissingArgumentException;

/**
 * API client for roles endpoint.
 *
 * @link https://help.getharvest.com/api-v2/roles-api/roles/roles/
 */
interface RolesInterface {

	/**
	 * Retrieves a list of roles.
	 *
	 * @return array
	 */
	public function all();

	/**
	 * Retrieves the role with the given ID.
	 *
	 * @param int $roleId The ID of the role.
	 * @return array|string
	 */
	public function show( int $roleId);

	/**
	 * Creates a new role object.
	 *
	 * @param array $parameters The parameters of the new role object.
	 * @return array|string
	 * @throws MissingArgumentException
	 * @throws InvalidArgumentException
	 *
	 */
	public function create( array $parameters);

	/**
	 * Updates the specific role by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $roleId The ID of the role.
	 * @param array $parameters
	 * @return array|string
	 * @throws InvalidArgumentException
	 *
	 * @throws MissingArgumentException
	 */
	public function update( int $roleId, array $parameters);

	/**
	 * Deletes a role.
	 *
	 * Deleting a role will unlink it from any users it was assigned to.
	 *
	 * @param int $roleId The ID of the role.
	 * @return array|string
	 */
	public function remove( int $roleId);
}
