<?php

namespace Required\Harvest\Api\TimeEntry;

/**
 * API client for external reference of a time entry endpoint.
 *
 * @link https://help.getharvest.com/api-v2/timesheets-api/timesheets/time-entries/
 */
interface ExternalReferenceInterface {

	/**
	 * Deletes a time entry’s external reference.
	 *
	 * @param int $timeEntryId The ID of the time entry.
	 * @return array|string
	 */
	public function remove( int $timeEntryId );
}
