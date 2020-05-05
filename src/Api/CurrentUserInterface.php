<?php

namespace Required\Harvest\Api;

use Required\Harvest\Api\CurrentUser\ProjectAssignmentsInterface;

/**
 * API client for users endpoint.
 *
 * @link https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/#personal-access-tokens
 */
interface CurrentUserInterface {

	/**
	 * Retrieves the authenticated user.
	 *
	 * @return array|string
	 */
	public function show();

	/**
	 * Gets the authenticated user's project assignments.
	 *
	 * @return \Required\Harvest\Api\CurrentUser\ProjectAssignmentsInterface ;
	 */
	public function projectAssignments(): ProjectAssignmentsInterface;
}
