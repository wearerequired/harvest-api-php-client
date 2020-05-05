<?php
/**
 * Users class.
 */

namespace Required\Harvest\Api;

use DateTime;

/**
 * API client for users endpoint.
 *
 * @link https://help.getharvest.com/api-v2/users-api/users/users/
 */
class Users extends AbstractApi implements UsersInterface {


	/**
	 * Retrieves a list of users.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of users. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active users and `false` to return
	 *                                          inactive users.
	 *     @type DateTime|string $updated_since Only return users that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] ) {
		if ( isset( $parameters['updated_since'] ) && $parameters['updated_since'] instanceof DateTime ) {
			$parameters['updated_since'] = $parameters['updated_since']->format( DateTime::ATOM );
		}

		if ( isset( $parameters['is_active'] ) ) {
			$parameters['is_active'] = filter_var( $parameters['is_active'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
		}

		$result = $this->get( '/users', $parameters );
		if ( ! isset( $result['users'] ) || ! \is_array( $result['users'] ) ) {
			throw new \Required\Harvest\Exception\RuntimeException( 'Unexpected result.' );
		}

		return $result['users'];
	}

	/**
	 * Retrieves the user with the given ID.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $userId The ID of the user.
	 * @return array|string
	 */
	public function show( int $userId ) {
		return $this->get( '/users/' . rawurlencode( $userId ) );
	}

	/**
	 * Creates a new user object.
	 *
	 * @throws \Http\Client\Exception
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new user object.
	 * @return array|string
	 */
	public function create( array $parameters ) {
		if ( ! isset( $parameters['first_name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'first_name' );
		}

		if ( ! isset( $parameters['last_name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'last_name' );
		}

		if ( ! isset( $parameters['email'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'email' );
		}

		if ( ! \is_string( $parameters['first_name'] ) || empty( trim( $parameters['first_name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "first_name" parameter must be a non-empty string.' );
		}

		if ( ! \is_string( $parameters['last_name'] ) || empty( trim( $parameters['last_name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "first_name" parameter must be a non-empty string.' );
		}

		if ( ! \is_string( $parameters['email'] ) || empty( trim( $parameters['email'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "email" parameter must be a non-empty string.' );
		}

		return $this->post( '/users', $parameters );
	}

	/**
	 * Updates the specific user by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $userId The ID of the user.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $userId, array $parameters ) {
		return $this->patch( '/users/' . rawurlencode( $userId ), $parameters );
	}

	/**
	 * Deletes a user.
	 *
	 * Deleting a user is only possible if they have no time entries or expenses associated with them.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $userId The ID of the user.
	 * @return array|string
	 */
	public function remove( int $userId ) {
		return $this->delete( '/users/' . rawurlencode( $userId ) );
	}

	/**
	 * Gets a user's project assignments.
	 *
	 * @return \Required\Harvest\Api\User\ProjectAssignmentsInterface ;
	 */
	public function projectAssignments(): User\ProjectAssignmentsInterface {
		return new User\ProjectAssignments( $this->client );
	}
}
