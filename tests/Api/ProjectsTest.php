<?php
/**
 * ProjectsTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Project\TaskAssignments;
use Required\Harvest\Api\Project\UserAssignments;
use Required\Harvest\Api\Projects;

/**
 * Tests for projects endpoint.
 */
class ProjectsTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Projects::class;
	}

	/**
	 * Test retrieving all projects.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all projects with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects' )
			->will( $this->returnValue( $response ) );

		$api->all();
	}

	/**
	 * Test retrieving all active projects with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving all active projects with `'is_active' => 1`.
	 */
	public function testAllActiveIntegerTrue() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 1 ] ) );
	}

	/**
	 * Test retrieving all active projects with `'is_active' => 'true'`.
	 */
	public function testAllActiveStringTrue() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 'true' ] ) );
	}

	/**
	 * Test retrieving all projects with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all projects with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'projects' );
		$expectedArray = $response['projects'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single project.
	 */
	public function testShow() {
		$projectId = 14308069;

		$expectedArray = $this->getFixture( 'project-' . $projectId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/projects/' . $projectId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $projectId ) );
	}

	/**
	 * Test creating new project with no client ID.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingClientId() {
		$api = $this->getApiMock();

		$data = [
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with invalid client ID.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidClientId() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 0,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with no name.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingName() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with invalid name.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidName() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => '',
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with no billable.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingBillable() {
		$api = $this->getApiMock();

		$data = [
			'client_id' => 5735776,
			'name'      => 'Your New Project',
			'bill_by'   => 'Project',
			'budget_by' => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with invalid billable.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidBillable() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => 'invalid',
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with no bill by.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingBillBy() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with invalid bill by.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidBillBy() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Invalid',
			'budget_by'   => 'project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with no budget by.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingBudgetBy() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Project',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project with invalid bill by.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidBudgetBy() {
		$api = $this->getApiMock();

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'Invalid',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new project.
	 */
	public function testCreateNewProject() {
		$expectedArray = $this->getFixture( 'project-5735776' );

		$data = [
			'client_id'   => 5735776,
			'name'        => 'Your New Project',
			'is_billable' => true,
			'bill_by'     => 'Project',
			'budget_by'   => 'project',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/projects', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating project.
	 */
	public function testUpdateProject() {
		$expectedArray = $this->getFixture( 'project-14308112' );

		$projectId = 14308112;
		$data      = [
			'name' => 'New project name',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/projects/' . $projectId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $projectId, $data ) );
	}

	/**
	 * Test deleting project.
	 */
	public function testDeleteProject() {
		$projectId = 14308112;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/projects/' . $projectId );

		$api->remove( $projectId );
	}

	/**
	 * Test API interface for user assignments.
	 */
	public function testUserAssignments() {
		$api = $this->getApiMock();

		$this->assertInstanceOf( UserAssignments::class, $api->userAssignments() );
	}

	/**
	 * Test API interface for task assignments.
	 */
	public function testTaskAssignments() {
		$api = $this->getApiMock();

		$this->assertInstanceOf( TaskAssignments::class, $api->taskAssignments() );
	}
}
