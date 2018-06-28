<?php
/**
 * ExternalReferenceTest class.
 */

namespace Required\Harvest\Tests\Api\TimeEntry;

use Required\Harvest\Api\TimeEntry\ExternalReference;
use Required\Harvest\Tests\Api\TestCase;

/**
 * Tests for external reference of a time entry endpoint.
 */
class ExternalReferenceTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return ExternalReference::class;
	}

	/**
	 * Test a time entry’s external reference.
	 */
	public function testDelete() {
		$timeEntryId = 636718192;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/time_entries/' . $timeEntryId . '/external_reference' );

		$api->remove( $timeEntryId );
	}
}
