<?php

namespace Required\Harvest\Api;

/**
 * API client for users endpoint.
 *
 * @link https://help.getharvest.com/api-v2/users-api/users/users/
 */
interface UsersInterface {

	/**
	 * Retrieves a list of users.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of users. Default empty array.
	 *
	 *      @type bool $is_active                Pass `true` to only return active users and `false` to return
	 *                                           inactive users.
	 *      @type DateTime|string $updated_since Only return users that have been updated since the given
	 *                                           date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the user with the given ID.
	 *
	 * @param int $userId The ID of the user.
	 * @return array|string
	 */
	public function show( int $userId );

	/**
	 * Creates a new user object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new user object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific user by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $userId The ID of the user.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $userId, array $parameters );

	/**
	 * Deletes a user.
	 *
	 * Deleting a user is only possible if they have no time entries or expenses associated with them.
	 *
	 * @param int $userId The ID of the user.
	 * @return array|string
	 */
	public function remove( int $userId );

	/**
	 * Gets a user's project assignments.
	 *
	 * @return \Required\Harvest\Api\User\ProjectAssignmentsInterface ;
	 */
	public function projectAssignments(): User\ProjectAssignmentsInterface;
}
