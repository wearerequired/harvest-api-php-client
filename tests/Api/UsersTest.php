<?php
/**
 * UsersTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\User\ProjectAssignments;
use Required\Harvest\Api\Users;

/**
 * Tests for users endpoint.
 */
class UsersTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Users::class;
	}

	/**
	 * Test retrieving all users.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all users.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );
		$api->all();
	}

	/**
	 * Test retrieving all active users with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving all active users with `'is_active' => 1`.
	 */
	public function testAllActiveIntegerTrue() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 1 ] ) );
	}

	/**
	 * Test retrieving all active users with `'is_active' => 'true'`.
	 */
	public function testAllActiveStringTrue() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 'true' ] ) );
	}

	/**
	 * Test retrieving all users with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all users with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'users' );
		$expectedArray = $response['users'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single user.
	 */
	public function testShow() {
		$userId = 1782974;

		$expectedArray = $this->getFixture( 'user-' . $userId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users/' . $userId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $userId ) );
	}

	/**
	 * Test creating new user with no first name.
	 */
	public function testCreateMissingFirstName() {
		$api = $this->getApiMock();

		$data = [
			'last_name' => 'last_name',
			'email'     => 'email',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user with no last name.
	 */
	public function testCreateMissingLasttName() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 'first_name',
			'email'      => 'email',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user with no email.
	 */
	public function testCreateMissingEmail() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 'first_name',
			'last_name'  => 'last_name',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user with invalid first name.
	 */
	public function testCreateInvalidFirstName() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 0,
			'last_name'  => 'last_name',
			'email'      => 'email',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user with invalid last name.
	 */
	public function testCreateInvalidLasttName() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 'first_name',
			'last_name'  => ' ',
			'email'      => 'email',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user with invalid email.
	 */
	public function testCreateInvalidEmail() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 'first_name',
			'last_name'  => 'last_name',
			'email'      => false,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new user.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'user-3' );

		$data = [
			'first_name' => $expectedArray['first_name'],
			'last_name'  => $expectedArray['last_name'],
			'email'      => $expectedArray['email'],
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/users', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating user.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'user-2' );

		$userId = 2;
		$data   = [
			'telephone' => '888-555-1212',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/users/' . $userId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $userId, $data ) );
	}

	/**
	 * Test deleting user.
	 */
	public function testDelete() {
		$userId = 2;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/users/' . $userId );

		$api->remove( $userId );
	}

	/**
	 * Test API interface for project assignments.
	 */
	public function testProjectAssignments() {
		$api = $this->getApiMock();

		$this->assertInstanceOf( ProjectAssignments::class, $api->projectAssignments() );
	}
}
