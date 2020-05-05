<?php

namespace Required\Harvest\Api\Project;

/**
 * API client for project user assignments endpoint.
 *
 * @link https://help.getharvest.com/api-v2/projects-api/projects/user-assignments/
 */
interface UserAssignmentsInterface {

	/**
	 * Retrieves a list of user assignments for a specific project.
	 *
	 * @param int   $projectId  The ID of the project.
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of user assignments. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active user assignments and `false` to
	 *                                          return  inactive user assignments.
	 *     @type DateTime|string $updated_since Only return user assignments that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( int $projectId, array $parameters = [] );

	/**
	 * Retrieves the user assignment with the given ID.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $userAssignmentId The ID of the user assignment.
	 * @return array|string
	 */
	public function show( int $projectId, int $userAssignmentId );

	/**
	 * Creates a new user assignment object.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 *
	 * @param int $projectId The ID of the project.
	 * @param array $parameters The parameters of the new user assignment object.
	 * @return array|string
	 */
	public function create( int $projectId, array $parameters );

	/**
	 * Updates the specific user assignment by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $userAssignmentId The ID of the user assignment.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $projectId, int $userAssignmentId, array $parameters );

	/**
	 * Deletes a user assignment.
	 *
	 * Deleting a user assignment is only possible if it has no time entries or expenses associated with it.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $userAssignmentId The ID of the user assignment.
	 * @return array|string
	 */
	public function remove( int $projectId, int $userAssignmentId );
}
