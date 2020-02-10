<?php
/**
 * CurrentUser class.
 */

namespace Required\Harvest\Api;

use Http\Client\Exception;
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
	 * @return array|string
	 * @throws Exception
	 */
	public function show() {
		return $this->get( '/users/me' );
	}

	/**
	 * Gets the authenticated user's project assignments.
	 *
	 * @return ProjectAssignmentsInterface
	 */
	public function projectAssignments(): ProjectAssignmentsInterface {
		return new ProjectAssignments( $this->client );
	}
}
