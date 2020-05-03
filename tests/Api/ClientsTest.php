<?php
/**
 * ClientsTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Clients;
use Required\Harvest\Util\Currencies;

/**
 * Tests for clients endpoint.
 */
class ClientsTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Clients::class;
	}

	/**
	 * Test retrieving all clients.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all clients with invalid response.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );
		$api->all();
	}

	/**
	 * Test retrieving all active clients with `'is_active' => true`.
	 */
	public function testAllActiveBooleanTrue() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => true ] ) );
	}

	/**
	 * Test retrieving all active clients with `'is_active' => 1`.
	 */
	public function testAllActiveIntegerTrue() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 1 ] ) );
	}

	/**
	 * Test retrieving all active clients with `'is_active' => 'true'`.
	 */
	public function testAllActiveStringTrue() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients', [ 'is_active' => 'true' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'is_active' => 'true' ] ) );
	}

	/**
	 * Test retrieving all clients with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all clients with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'clients' );
		$expectedArray = $response['clients'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single client.
	 */
	public function testShow() {
		$clientId = 5735776;

		$expectedArray = $this->getFixture( 'client-' . $clientId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/clients/' . $clientId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $clientId ) );
	}

	/**
	 * Test creating new client with no name.
	 */
	public function testCreateMissingName() {
		$api = $this->getApiMock();

		$data = [
			'currency' => Currencies::EURO,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new client with invalid name.
	 */
	public function testCreateInvalidName() {
		$api = $this->getApiMock();

		$data = [
			'name'     => '',
			'currency' => Currencies::EURO,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new client.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'client-5737336' );

		$data = [
			'name'     => 'Your New Client',
			'currency' => Currencies::EURO,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/clients', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating client.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'client-5737336' );

		$clientId = 5737336;
		$data     = [
			'is_active' => false,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/clients/' . $clientId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $clientId, $data ) );
	}

	/**
	 * Test deleting client.
	 */
	public function testDelete() {
		$clientId = 5737336;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/clients/' . $clientId );

		$api->remove( $clientId );
	}
}
