<?php
/**
 * Roles class.
 */

namespace Required\Harvest\Api;

/**
 * API client for roles endpoint.
 *
 * @link https://help.getharvest.com/api-v2/roles-api/roles/roles/
 */
class Roles extends AbstractApi implements RolesInterface {


	/**
	 * Retrieves a list of roles.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @return array
	 */
	public function all() {
		$result = $this->get( '/roles' );
		if ( ! isset( $result['roles'] ) || ! \is_array( $result['roles'] ) ) {
			throw new \Required\Harvest\Exception\RuntimeException( 'Unexpected result.' );
		}

		return $result['roles'];
	}

	/**
	 * Retrieves the role with the given ID.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $roleId The ID of the role.
	 * @return array|string
	 */
	public function show( int $roleId ) {
		return $this->get( '/roles/' . rawurlencode( $roleId ) );
	}

	/**
	 * Creates a new role object.
	 *
	 * @throws \Http\Client\Exception
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new role object.
	 * @return array|string
	 */
	public function create( array $parameters ) {
		if ( ! isset( $parameters['name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'name' );
		}

		if ( ! \is_string( $parameters['name'] ) || empty( trim( $parameters['name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "name" parameter must be a non-empty string.' );
		}

		return $this->post( '/roles', $parameters );
	}

	/**
	 * Updates the specific role by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @throws \Http\Client\Exception
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 *
	 * @param int $roleId The ID of the role.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $roleId, array $parameters ) {
		if ( ! isset( $parameters['name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'name' );
		}

		if ( ! \is_string( $parameters['name'] ) || empty( trim( $parameters['name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "name" parameter must be a non-empty string.' );
		}

		return $this->patch( '/roles/' . rawurlencode( $roleId ), $parameters );
	}

	/**
	 * Deletes a role.
	 *
	 * Deleting a role will unlink it from any users it was assigned to.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $roleId The ID of the role.
	 * @return array|string
	 */
	public function remove( int $roleId ) {
		return $this->delete( '/roles/' . rawurlencode( $roleId ) );
	}
}
