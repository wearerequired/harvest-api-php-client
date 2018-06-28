<?php
/**
 * UserAssignmentsTest class.
 */

namespace Required\Harvest\Tests\Api\Project;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Project\UserAssignments;
use Required\Harvest\Tests\Api\TestCase;

/**
 * Tests for project user assignments endpoint.
 */
class UserAssignmentsTest extends TestCase {

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
		return UserAssignments::class;
	}

	/**
	 * Test retrieving all project user assignments.
	 */
	public function testAll() {
		$response      = $this->getFixture( "project-{$this->projectId}-user-assignments" );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments" )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId ) );
	}

	/**
	 * Test retrieving all project user assignments with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments" )
			->will( $this->returnValue( $response ) );

		$api->all( $this->projectId );
	}

	/**
	 * Test retrieving all project user assignments with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( "project-{$this->projectId}-user-assignments" );
		$expectedArray = $response['user_assignments'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments", [ 'updated_since' => $updatedSince->format( 'Y-m-d H:i' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all project user assignments with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( "project-{$this->projectId}-user-assignments" );
		$expectedArray = $response['user_assignments'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments", [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all active project user assignments with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( "project-{$this->projectId}-user-assignments" );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments", [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( $this->projectId, [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving single project user assignment.
	 */
	public function testShow() {
		$userAssignmentId = 125068554;

		$expectedArray = $this->getFixture( "project-{$this->projectId}-user-assignment-{$userAssignmentId}" );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( "/projects/{$this->projectId}/user_assignments/{$userAssignmentId}" )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $this->projectId, $userAssignmentId ) );
	}

	/**
	 * Test creating new project user assignment with no user ID.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingUserId() {
		$api = $this->getApiMock();

		$data = [
			'is_active'          => true,
			'is_project_manager' => true,
			'hourly_rate'        => 75.50,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $this->projectId, $data );
	}

	/**
	 * Test creating new project user assignment with invalid user ID.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidUserId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'            => 0,
			'is_active'          => true,
			'is_project_manager' => true,
			'hourly_rate'        => 75.50,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $this->projectId, $data );
	}

	/**
	 * Test creating new project user assignment.
	 */
	public function testCreateNew() {
		$userAssignmentId = 125068758;
		$expectedArray    = $this->getFixture( "project-{$this->projectId}-user-assignment-{$userAssignmentId}" );

		$data = [
			'user_id'            => $userAssignmentId,
			'is_active'          => true,
			'is_project_manager' => true,
			'hourly_rate'        => 75.50,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( "/projects/{$this->projectId}/user_assignments", $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $this->projectId, $data ) );
	}

	/**
	 * Test updating project user assignment.
	 */
	public function testUpdate() {
		$userAssignmentId = 125068758;
		$expectedArray    = $this->getFixture( "project-{$this->projectId}-user-assignment-{$userAssignmentId}" );

		$data = [
			'budget' => 120,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( "/projects/{$this->projectId}/user_assignments/{$userAssignmentId}", $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $this->projectId, $userAssignmentId, $data ) );
	}

	/**
	 * Test deleting project user assignment.
	 */
	public function testDelete() {
		$userAssignmentId = 125068758;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( "/projects/{$this->projectId}/user_assignments/{$userAssignmentId}" );

		$api->remove( $this->projectId, $userAssignmentId );
	}
}
