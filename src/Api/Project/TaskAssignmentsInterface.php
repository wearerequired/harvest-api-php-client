<?php

namespace Required\Harvest\Api\Project;

/**
 * API client for project task assignments endpoint.
 *
 * @link https://help.getharvest.com/api-v2/projects-api/projects/task-assignments/
 */
interface TaskAssignmentsInterface {

	/**
	 * Retrieves a list of task assignments for a specific project.
	 *
	 * @param int   $projectId  The ID of the project.
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of task assignments. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active task assignments and `false` to
	 *                                          return  inactive task assignments.
	 *     @type DateTime|string $updated_since Only return task assignments that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( int $projectId, array $parameters = [] );

	/**
	 * Retrieves the task assignment with the given ID.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $taskAssignmentId The ID of the task assignment.
	 * @return array|string
	 */
	public function show( int $projectId, int $taskAssignmentId );

	/**
	 * Creates a new task assignment object.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 *
	 * @param int $projectId The ID of the project.
	 * @param array $parameters The parameters of the new task assignment object.
	 * @return array|string
	 */
	public function create( int $projectId, array $parameters );

	/**
	 * Updates the specific task assignment by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $taskAssignmentId The ID of the task assignment.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $projectId, int $taskAssignmentId, array $parameters );

	/**
	 * Deletes a task assignment.
	 *
	 * Deleting a task assignment is only possible if it has no time entries associated with it.
	 *
	 * @param int $projectId The ID of the project.
	 * @param int $taskAssignmentId The ID of the task assignment.
	 * @return array|string
	 */
	public function remove( int $projectId, int $taskAssignmentId );
}
