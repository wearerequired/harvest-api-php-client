<?php
/**
 * TaskAssignments class.
 */

namespace Required\Harvest\Tests\Api\Project;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Project\TaskAssignments;
use Required\Harvest\Tests\Api\TestCase;

/**
 * Tests for project task assignments endpoint.
 */
class TaskAssignmentsTest extends TestCase {

	/**
	 * Project ID for testing.
	 * @var int
	 */
	protected $projectId = 14308069;

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return TaskAssignments::class;
	}

	/**
	 * Test retrieving all project task assignments.
	 */
	public function testAll() {
		$response      = $this->getFixture( "project-{$this->projectId}-task-assignments" );
		$expectedArray = $response['task_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments" )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId ) );
	}

	/**
	 * Test retrieving all project task assignments with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments" )
			->will( $this->returnValue( $response ) );

		$api->all( $this->projectId );
	}

	/**
	 * Test retrieving all project task assignments with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( "project-{$this->projectId}-task-assignments" );
		$expectedArray = $response['task_assignments'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments", [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all project task assignments with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( "project-{$this->projectId}-task-assignments" );
		$expectedArray = $response['task_assignments'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments", [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all active project task assignments with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( "project-{$this->projectId}-task-assignments" );
		$expectedArray = $response['task_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments", [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving single project task assignment.
	 */
	public function testShow() {
		$taskAssignmentId = 155505016;

		$expectedArray = $this->getFixture( "project-{$this->projectId}-task-assignment-{$taskAssignmentId}" );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/task_assignments/{$taskAssignmentId}" )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $this->projectId, $taskAssignmentId ) );
	}

	/**
	 * Test creating new project task assignment with no task ID.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingTaskId() {
		$api = $this->getApiMock();

		$data = [
			'is_active'   => true,
			'billable'    => true,
			'hourly_rate' => 75.50,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $this->projectId, $data );
	}

	/**
	 * Test creating new project task assignment with invalid task ID.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidTaskId() {
		$api = $this->getApiMock();

		$data = [
			'task_id'     => 0,
			'is_active'   => true,
			'billable'    => true,
			'hourly_rate' => 75.50,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $this->projectId, $data );
	}

	/**
	 * Test creating new project task assignment.
	 */
	public function testCreateNew() {
		$taskAssignmentId = 155506339;
		$expectedArray    = $this->getFixture( "project-{$this->projectId}-task-assignment-{$taskAssignmentId}" );

		$data = [
			'task_id'     => 8083800,
			'is_active'   => true,
			'billable'    => true,
			'hourly_rate' => 75.50,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( "/projects/{$this->projectId}/task_assignments", $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $this->projectId, $data ) );
	}

	/**
	 * Test updating project task assignment.
	 */
	public function testUpdate() {
		$taskAssignmentId = 155506339;
		$expectedArray    = $this->getFixture( "project-{$this->projectId}-task-assignment-{$taskAssignmentId}" );

		$data = [
			'budget' => 120,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( "/projects/{$this->projectId}/task_assignments/{$taskAssignmentId}", $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $this->projectId, $taskAssignmentId, $data ) );
	}

	/**
	 * Test deleting project task assignment.
	 */
	public function testDelete() {
		$taskAssignmentId = 155506339;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( "/projects/{$this->projectId}/task_assignments/{$taskAssignmentId}" );

		$api->remove( $this->projectId, $taskAssignmentId );
	}
}
