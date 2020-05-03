<?php
/**
 * TasksTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Tasks;

/**
 * Tests for tasks endpoint.
 */
class TasksTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Tasks::class;
	}

	/**
	 * Test retrieving all tasks.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all tasks with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks' )
			->will( $this->returnValue( $response ) );

		$api->all();
	}

	/**
	 * Test retrieving all active tasks with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving all active tasks with `'is_active' => 1`.
	 */
	public function testAllActiveIntegerTrue() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 1 ] ) );
	}

	/**
	 * Test retrieving all active tasks with `'is_active' => 'true'`.
	 */
	public function testAllActiveStringTrue() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 'true' ] ) );
	}

	/**
	 * Test retrieving all tasks with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all tasks with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'tasks' );
		$expectedArray = $response['tasks'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single task.
	 */
	public function testShow() {
		$taskId = 8083800;

		$expectedArray = $this->getFixture( 'task-' . $taskId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/tasks/' . $taskId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $taskId ) );
	}

	/**
	 * Test creating new task with no name.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingName() {
		$api = $this->getApiMock();

		$data = [
			'hourly_rate' => 120.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new task with invalid name.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidName() {
		$api = $this->getApiMock();

		$data = [
			'name'        => '',
			'hourly_rate' => 120.0,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new task.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'task-8083782' );

		$data = [
			'name'        => 'New Task Name',
			'hourly_rate' => true,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/tasks', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating task.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'task-8083782' );

		$taskId = 8083782;
		$data   = [
			'is_default' => true,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/tasks/' . $taskId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $taskId, $data ) );
	}

	/**
	 * Test deleting task.
	 */
	public function testDelete() {
		$taskId = 8083782;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/tasks/' . $taskId );

		$api->remove( $taskId );
	}
}
