<?php
/**
 * CurrentUser class.
 */

namespace Required\Harvest\Api;

use Required\Harvest\Api\CurrentUser\ProjectAssignments;
use Required\Harvest\Api\CurrentUser\ProjectAssignmentsInterface;

/**
 * API client for users endpoint.
 *
 * @link https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/#personal-access-tokens
 */
class CurrentUser extends AbstractApi implements CurrentUserInterface {


	/**
	 * Retrieves the authenticated user.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @return array|string
	 */
	public function show() {
		return $this->get( '/users/me' );
	}

	/**
	 * Gets the authenticated user's project assignments.
	 *
	 * @return \Required\Harvest\Api\CurrentUser\ProjectAssignmentsInterface
	 */
	public function projectAssignments(): ProjectAssignmentsInterface {
		return new ProjectAssignments( $this->client );
	}
}
