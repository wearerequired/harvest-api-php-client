<?php
/**
 * TimeEntriesTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\TimeEntries;
use Required\Harvest\Api\TimeEntry\ExternalReference;

/**
 * Tests for time entries endpoint.
 */
class TimeEntriesTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return TimeEntries::class;
	}

	/**
	 * Test retrieving all time entries.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all time entries with invalid response.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );
		$api->all();
	}

	/**
	 * Test retrieving all time entries with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all time entries with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all time entries with `'from' => DateTime`.
	 */
	public function testAllFromWithDateTime() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'from' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'from' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all time entries with `'to' => DateTime`.
	 */
	public function testAllToWithDateTime() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'to' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'to' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all active time entries with `'is_billed' => true`.
	 */
	public function testAllIsBilledBooleanTrue() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'is_billed' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_billed' => true ] ) );
	}

	/**
	 * Test retrieving all active time entries with `'is_running' => false`.
	 */
	public function testAllIsRunningBooleanFalse() {
		$response      = $this->getFixture( 'time-entries' );
		$expectedArray = $response['time_entries'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries', [ 'is_running' => 'false' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_running' => false ] ) );
	}

	/**
	 * Test retrieving single time entry.
	 */
	public function testShow() {
		$timeEntryId = 636708723;

		$expectedArray = $this->getFixture( 'time-entry-' . $timeEntryId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/time_entries/' . $timeEntryId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $timeEntryId ) );
	}

	/**
	 * Test creating new time entry with no project ID.
	 */
	public function testCreateMissingProjectId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'task_id'    => 8083365,
			'spent_date' => '2017-03-21',
			'hours'      => 1.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry with invalid project ID.
	 */
	public function testCreateInvalidProjectId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 0,
			'task_id'    => 8083365,
			'spent_date' => '2017-03-21',
			'hours'      => 1.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry with no task ID.
	 */
	public function testCreateMissingTaskId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'spent_date' => '2017-03-21',
			'hours'      => 1.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry with invalid task ID.
	 */
	public function testCreateInvalidTaskId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 0,
			'spent_date' => '2017-03-21',
			'hours'      => 1.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry with no spent date.
	 */
	public function testCreateMissingSpentDate() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 8083365,
			'hours'      => 1.0,
		];
		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry with invalid spent date.
	 */
	public function testCreateInvalidSpentDate() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 8083365,
			'spent_date' => 0,
			'hours'      => 1.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new time entry.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'time-entry-636718192' );

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 8083365,
			'spent_date' => '2017-03-21',
			'hours'      => 1.0,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/time_entries', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test creating new time entry with date time object.
	 */
	public function testCreateNewWithDateTimeAsSpentDate() {
		$expectedArray = $this->getFixture( 'time-entry-636718192' );

		$data_input = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 8083365,
			'spent_date' => date_create( '2017-03-01' ),
			'hours'      => 1.0,
		];

		$data_response = [
			'user_id'    => 1782959,
			'project_id' => 14307913,
			'task_id'    => 8083365,
			'spent_date' => '2017-03-01T00:00:00+00:00',
			'hours'      => 1.0,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/time_entries', $data_response )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data_input ) );
	}

	/**
	 * Test updating time entry.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'time-entry-636718192' );

		$timeEntryId = 636718192;
		$data        = [
			'notes' => 'Updated notes',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/time_entries/' . $timeEntryId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $timeEntryId, $data ) );
	}

	/**
	 * Test deleting time entry.
	 */
	public function testDelete() {
		$timeEntryId = 636718192;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/time_entries/' . $timeEntryId );

		$api->remove( $timeEntryId );
	}

	/**
	 * Test restarting time entry.
	 */
	public function testRestart() {
		$timeEntryId = 636718192;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/time_entries/' . $timeEntryId . '/restart' );

		$api->restart( $timeEntryId );
	}

	/**
	 * Test stopping time entry.
	 */
	public function testStop() {
		$timeEntryId = 636718192;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/time_entries/' . $timeEntryId . '/stop' );

		$api->stop( $timeEntryId );
	}

	/**
	 * Test API interface for external reference.
	 */
	public function testExternalReference() {
		$api = $this->getApiMock();

		$this->assertInstanceOf( ExternalReference::class, $api->externalReference() );
	}
}
