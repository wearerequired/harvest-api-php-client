<?php

namespace Required\Harvest\Api;

/**
 * API client for task assignments endpoint.
 *
 * @link https://help.getharvest.com/api-v2/projects-api/projects/task-assignments/
 */
interface TaskAssignmentsInterface {

	/**
	 * Retrieves a list of task assignments.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of task assignments. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active task assignments and `false` to
	 *                                          return  inactive task assignments.
	 *     @type DateTime|string $updated_since Only return user assignments that have been updated since the given
	 *                                          date and time.
	 * }
	  * @return array
	 */
	public function all( array $parameters = [] );
}
