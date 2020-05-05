<?php

namespace Required\Harvest\Api\CurrentUser;

/**
 * API client for current user project assignments endpoint.
 *
 * @link https://help.getharvest.com/api-v2/users-api/users/project-assignments/
 */
interface ProjectAssignmentsInterface {

	/**
	 * Retrieves a list of project assignments for the current user.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of project assignments. Default empty array.
	 *
	 *     @type DateTime|string $updated_since Only return project assignments that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );
}
