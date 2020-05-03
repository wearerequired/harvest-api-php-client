<?php
/**
 * RolesTest class.
 */

namespace Required\Harvest\Tests\Api;

use Required\Harvest\Api\Roles;

/**
 * Tests for roles endpoint.
 */
class RolesTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Roles::class;
	}

	/**
	 * Test retrieving all roles.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'roles' );
		$expectedArray = $response['roles'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/roles' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all roles with invalid response.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/roles' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );
		$api->all();
	}

	/**
	 * Test retrieving single role.
	 */
	public function testShow() {
		$roleId = 1782974;

		$expectedArray = $this->getFixture( 'role-' . $roleId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/roles/' . $roleId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $roleId ) );
	}

	/**
	 * Test creating new role with no name.
	 */
	public function testCreateMissingName() {
		$api = $this->getApiMock();

		$data = [
			'user_ids' => [ 1234 ],
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new role with invalid name.
	 */
	public function testCreateInvalidName() {
		$api = $this->getApiMock();

		$data = [
			'name'     => '',
			'user_ids' => [ 1234 ],
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new role.
	 */
	public function testCreateRole() {
		$expectedArray = $this->getFixture( 'role-2' );

		$data = [
			'name'     => $expectedArray['name'],
			'user_ids' => $expectedArray['user_ids'],
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/roles', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating role
	 */
	public function testUpdateRole() {
		$expectedArray = $this->getFixture( 'role-2-update' );

		$roleId = 2;
		$data   = [
			'name'     => $expectedArray['name'],
			'user_ids' => $expectedArray['user_ids'],
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/roles/' . $roleId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $roleId, $data ) );
	}

	/**
	 * Test updating role with missing name.
	 */
	public function testUpdateRoleMissingName() {
		$expectedArray = $this->getFixture( 'role-2-update' );

		$roleId = 2;
		$data   = [
			'user_ids' => $expectedArray['user_ids'],
		];

		$api = $this->getApiMock();
		$api->expects( $this->never() )
			->method( 'patch' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->update( $roleId, $data );
	}

	/**
	 * Test updating role with invalid name.
	 */
	public function testUpdateRoleInvalidName() {
		$expectedArray = $this->getFixture( 'role-2-update' );

		$roleId = 2;
		$data   = [
			'name'     => '',
			'user_ids' => $expectedArray['user_ids'],
		];

		$api = $this->getApiMock();
		$api->expects( $this->never() )
			->method( 'patch' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->update( $roleId, $data );
	}

	/**
	 * Test deleting user.
	 */
	public function testDeleteRole() {
		$roleId = 2;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/roles/' . $roleId );

		$api->remove( $roleId );
	}
}
