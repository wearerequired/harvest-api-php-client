<?php
/**
 * TimeEntries class.
 */

namespace Required\Harvest\Api;

use DateTime;
use Required\Harvest\Api\TimeEntry\ExternalReference;
use Required\Harvest\Api\TimeEntry\ExternalReferenceInterface;

/**
 * API client for time entries endpoint.
 *
 * @link https://help.getharvest.com/api-v2/timesheets-api/timesheets/time-entries/
 */
class TimeEntries extends AbstractApi implements TimeEntriesInterface {


	/**
	 * Retrieves a list of time entries.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of time entries. Default empty array.
	 *
	 *     @type int              $user_id       Only return time entries belonging to the user with the given ID.
	 *     @type int              $client_id     Only return time entries belonging to the client with the given ID.
	 *     @type int              $project_id    Only return time entries belonging to the project with the given ID.
	 *     @type bool             $is_billed     Pass `true` to only return time entries that have been invoiced and
	 *                                           `false` to return time entries that have not been invoiced.
	 *     @type bool             $is_running    Pass `true` to only return running time entries and `false` to return
	 *                                           non-running time entries.
	 *     @type DateTime|string $updated_since  Only return time entries that have been updated since the given
	 *                                           date and time.
	 *     @type DateTime|string $from           Only return time entries with a `spent_date` on or after the given date.
	 *     @type DateTime|string $to             Only return time entries with a `spent_date` on or after the given date.
	 * }
	  * @return array
	 */
	public function all( array $parameters = [] ) {
		if ( isset( $parameters['updated_since'] ) && $parameters['updated_since'] instanceof DateTime ) {
			$parameters['updated_since'] = $parameters['updated_since']->format( DateTime::ATOM );
		}

		if ( isset( $parameters['from'] ) && $parameters['from'] instanceof DateTime ) {
			$parameters['from'] = $parameters['from']->format( 'Y-m-d' );
		}

		if ( isset( $parameters['to'] ) && $parameters['to'] instanceof DateTime ) {
			$parameters['to'] = $parameters['to']->format( 'Y-m-d' );
		}

		if ( isset( $parameters['is_billed'] ) ) {
			$parameters['is_billed'] = filter_var( $parameters['is_billed'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
		}

		if ( isset( $parameters['is_running'] ) ) {
			$parameters['is_running'] = filter_var( $parameters['is_running'], FILTER_VALIDATE_BOOLEAN ) ? 'true' : 'false';
		}

		$result = $this->get( '/time_entries', $parameters );
		if ( ! isset( $result['time_entries'] ) || ! \is_array( $result['time_entries'] ) ) {
			throw new \Required\Harvest\Exception\RuntimeException( 'Unexpected result.' );
		}

		return $result['time_entries'];
	}

	/**
	 * Retrieves the time entry with the given ID.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function show( int $timeEntryId ) {
		return $this->get( '/time_entries/' . rawurlencode( $timeEntryId ) );
	}

	/**
	 * Creates a new time entry object.
	 *
	 * @throws \Http\Client\Exception
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new time entry object.
	 * @return array|string
	 */
	public function create( array $parameters ) {
		if ( ! isset( $parameters['project_id'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'project_id' );
		}

		if ( ! isset( $parameters['task_id'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'task_id' );
		}

		if ( ! isset( $parameters['spent_date'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'spent_date' );
		}

		if ( ! \is_int( $parameters['project_id'] ) || empty( $parameters['project_id'] ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "project_id" parameter must be a non-empty integer.' );
		}

		if ( ! \is_int( $parameters['task_id'] ) || empty( $parameters['task_id'] ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "task_id" parameter must be a non-empty integer.' );
		}

		if ( ! \is_string( $parameters['spent_date'] ) && ! $parameters['spent_date'] instanceof DateTime ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "spent_date" parameter must be DateTime instance or an ISO 8601 formatted date string.' );
		}

		if ( $parameters['spent_date'] instanceof DateTime ) {
			$parameters['spent_date'] = $parameters['spent_date']->format( DateTime::ATOM );
		}

		return $this->post( '/time_entries', $parameters );
	}

	/**
	 * Updates the specific time entry by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $timeEntryId, array $parameters ) {
		return $this->patch( '/time_entries/' . rawurlencode( $timeEntryId ), $parameters );
	}

	/**
	 * Deletes a time entry.
	 *
	 * Deleting a time entry is only possible if it’s not closed and the associated project and task haven’t been
	 * archived. However, Admins can delete closed entries.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function remove( int $timeEntryId ) {
		return $this->delete( '/time_entries/' . rawurlencode( $timeEntryId ) );
	}

	/**
	 * Restarts a time entry.
	 *
	 * Restarting a time entry is only possible if it isn’t currently running.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function restart( int $timeEntryId ) {
		return $this->patch( '/time_entries/' . rawurlencode( $timeEntryId ) . '/restart' );
	}

	/**
	 * Stops a time entry.
	 *
	 * Stopping a time entry is only possible if it’s currently running.
	 *
	 * @throws \Http\Client\Exception
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function stop( int $timeEntryId ) {
		return $this->patch( '/time_entries/' . rawurlencode( $timeEntryId ) . '/stop' );
	}

	/**
	 * Gets a time entry's external reference.
	 *
	 * This only supports removing an external reference.
	 *
	 * @return \Required\Harvest\Api\TimeEntry\ExternalReferenceInterface
	 */
	public function externalReference(): ExternalReferenceInterface {
		return new ExternalReference( $this->client );
	}
}
