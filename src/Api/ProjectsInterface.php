<?php

namespace Required\Harvest\Api;

/**
 * API client for projects endpoint.
 *
 * @link https://help.getharvest.com/api-v2/projects-api/projects/projects/
 */
interface ProjectsInterface {

	/**
	 * Retrieves a list of projects.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of projects. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active projects and `false` to return
	 *                                          inactive projects.
	 *     @type int $client_id                 Only return projects belonging to the client with the given ID.
	 *     @type DateTime|string $updated_since Only return projects that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the project with the given ID.
	 *
	 * @param int $projectId The ID of the project.
	 * @return array|string
	 */
	public function show( int $projectId );

	/**
	 * Creates a new project object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new project object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific project by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $projectId The ID of the project.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $projectId, array $parameters );

	/**
	 * Deletes a project.
	 *
	 * Deletes a project and any time entries or expenses tracked to it. However, invoices associated with the project
	 * will not be deleted. If you don’t want the project’s time entries and expenses to be deleted, you should archive
	 * the project instead:
	 *
	 *     $client->projects()->update( $projectId, [ 'is_active' => false ];
	 *
	 * @param int $projectId The ID of the project.
	 * @return array|string
	 */
	public function remove( int $projectId );

	/**
	 * Gets a projects's user assignments.
	 *
	 * @return \Required\Harvest\Api\Project\UserAssignmentsInterface
	 */
	public function userAssignments(): Project\UserAssignmentsInterface;

	/**
	 * Gets a projects's task assignments.
	 *
	 * @return \Required\Harvest\Api\Project\TaskAssignmentsInterface
	 */
	public function taskAssignments(): Project\TaskAssignmentsInterface;
}
