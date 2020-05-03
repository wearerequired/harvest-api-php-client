<?php
/**
 * ProjectAssignmentsTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\UserAssignments;

/**
 * Tests for current user assignments endpoint.
 */
class UserAssignmentsTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return UserAssignments::class;
	}

	/**
	 * Test retrieving all user assignments.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all user assignments with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments' )
			->will( $this->returnValue( $response ) );

		$api->all();
	}

	/**
	 * Test retrieving all user assignments with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all user assignments with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all active user assignments with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving all active user assignments with `'is_active' => 1`.
	 */
	public function testAllActiveIntegerTrue() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 1 ] ) );
	}

	/**
	 * Test retrieving all active user assignments with `'is_active' => 'true'`.
	 */
	public function testAllActiveStringTrue() {
		$response      = $this->getFixture( 'user-assignments' );
		$expectedArray = $response['user_assignments'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/user_assignments', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 'true' ] ) );
	}
}
