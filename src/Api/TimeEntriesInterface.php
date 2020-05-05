<?php

namespace Required\Harvest\Api;

use Required\Harvest\Api\TimeEntry\ExternalReferenceInterface;

/**
 * API client for time entries endpoint.
 *
 * @link https://help.getharvest.com/api-v2/timesheets-api/timesheets/time-entries/
 */
interface TimeEntriesInterface {

	/**
	 * Retrieves a list of time entries.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of time entries. Default empty array.
	 *
	 *     @type int $user_id                   Only return time entries belonging to the user with the given ID.
	 *     @type int $client_id                 Only return time entries belonging to the client with the given ID.
	 *     @type int $project_id                Only return time entries belonging to the project with the given ID.
	 *     @type bool $is_billed                Pass `true` to only return time entries that have been invoiced and
	 *                                          `false` to return time entries that have not been invoiced.
	 *     @type bool $is_running               Pass `true` to only return running time entries and `false` to return
	 *                                          non-running time entries.
	 *     @type DateTime|string $updated_since Only return time entries that have been updated since the given
	 *                                          date and time.
	 *     @type DateTime|string $from          Only return time entries with a `spent_date` on or after the given date.
	 *     @type DateTime|string $to            Only return time entries with a `spent_date` on or after the given date.
	 * }
	 * @return array
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the time entry with the given ID.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function show( int $timeEntryId );

	/**
	 * Creates a new time entry object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new time entry object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific time entry by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $timeEntryId, array $parameters );

	/**
	 * Deletes a time entry.
	 *
	 * Deleting a time entry is only possible if it’s not closed and the associated project and task haven’t been
	 * archived. However, Admins can delete closed entries.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function remove( int $timeEntryId );

	/**
	 * Restarts a time entry.
	 *
	 * Restarting a time entry is only possible if it isn’t currently running.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function restart( int $timeEntryId );

	/**
	 * Stops a time entry.
	 *
	 * Stopping a time entry is only possible if it’s currently running.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function stop( int $timeEntryId );

	/**
	 * Gets a time entry's external reference.
	 *
	 * This only supports removing an external reference.
	 *
	 * @return \Required\Harvest\Api\TimeEntry\ExternalReferenceInterface
	 */
	public function externalReference(): ExternalReferenceInterface;
}
