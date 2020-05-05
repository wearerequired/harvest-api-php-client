<?php

namespace Required\Harvest\Api;

/**
 * API client for tasks endpoint.
 *
 * @link https://help.getharvest.com/api-v2/tasks-api/tasks/tasks/
 */
interface TasksInterface {

	/**
	 * Retrieves a list of tasks.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of tasks. Default empty array.
	 *
	 *      @type bool $is_active                Pass `true` to only return active tasks and `false` to return
	 *                                           inactive tasks.
	 *      @type DateTime|string $updated_since Only return tasks that have been updated since the given
	 *                                           date and time.
	 * }
	 * @return array
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the task with the given ID.
	 *
	 * @param int $taskId The ID of the task.
	 * @return array|string
	 */
	public function show( int $taskId );

	/**
	 * Creates a new task object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new task object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific task by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $taskId The ID of the task.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $taskId, array $parameters );

	/**
	 * Deletes a task.
	 *
	 * Deleting a task is only possible if it has no time entries associated with it.
	 *
	 * @param int $taskId The ID of the task.
	 * @return array|string
	 */
	public function remove( int $taskId );
}
