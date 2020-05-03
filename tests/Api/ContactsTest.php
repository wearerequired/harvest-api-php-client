<?php
/**
 * ContactsTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Contacts;

/**
 * Tests for contacts endpoint.
 */
class ContactsTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Contacts::class;
	}

	/**
	 * Test retrieving all contacts.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'contacts' );
		$expectedArray = $response['contacts'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/contacts' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all contacts with invalid response.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/contacts' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );
		$api->all();
	}

	/**
	 * Test retrieving all contacts with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'contacts' );
		$expectedArray = $response['contacts'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/contacts', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all contacts with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'contacts' );
		$expectedArray = $response['contacts'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/contacts', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single contact.
	 */
	public function testShow() {
		$contactId = 4706479;

		$expectedArray = $this->getFixture( 'contact-' . $contactId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/contacts/' . $contactId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $contactId ) );
	}

	/**
	 * Test creating new contact with no client ID.
	 */
	public function testCreateMissingClientId() {
		$api = $this->getApiMock();

		$data = [
			'first_name' => 'George',
			'last_name'  => 'Frank',
			'email'      => 'georgefrank@example.com',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new contact with invalid client ID.
	 */
	public function testCreateInvalidClientId() {
		$api = $this->getApiMock();

		$data = [
			'client_id'  => 0,
			'first_name' => 'George',
			'last_name'  => 'Frank',
			'email'      => 'georgefrank@example.com',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new contact with no first name.
	 */
	public function testCreateMissingFirstName() {
		$api = $this->getApiMock();

		$data = [
			'client_id' => 5735776,
			'last_name' => 'Frank',
			'email'     => 'georgefrank@example.com',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new contact with invalid first name.
	 */
	public function testCreateInvalidFirstName() {
		$api = $this->getApiMock();

		$data = [
			'client_id'  => 5735776,
			'first_name' => '',
			'last_name'  => 'Frank',
			'email'      => 'georgefrank@example.com',
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new contact.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'contact-4706510' );

		$data = [
			'client_id'  => 5735776,
			'first_name' => 'George',
			'last_name'  => 'Frank',
			'email'      => 'georgefrank@example.com',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/contacts', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating contact.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'contact-4706510' );

		$contactId = 4706510;
		$data      = [
			'is_active' => false,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/contacts/' . $contactId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $contactId, $data ) );
	}

	/**
	 * Test deleting contact.
	 */
	public function testDelete() {
		$contactId = 4706510;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/contacts/' . $contactId );

		$api->remove( $contactId );
	}
}
