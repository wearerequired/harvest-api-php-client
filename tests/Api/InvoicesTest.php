<?php
/**
 * InvoicesTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Invoices;

/**
 * Tests for invoices endpoint.
 */
class InvoicesTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Invoices::class;
	}

	/**
	 * Test retrieving all invoices.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all invoices with invalid response.
	 *
	 * @expectedException \Required\Harvest\Exception\RuntimeException
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices' )
			->will( $this->returnValue( $response ) );

		$api->all();
	}

	/**
	 * Test retrieving all invoices with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices', [ 'updated_since' => $updatedSince->format( 'Y-m-d H:i' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all invoices with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all invoices with `'from' => DateTime`.
	 */
	public function testAllFromWithDateTime() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices', [ 'from' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'from' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all invoices with `'to' => DateTime`.
	 */
	public function testAllToWithDateTime() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices', [ 'to' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'to' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all invoices with `'state' => 'invalid`.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testAllStateInvalid() {
		$api = $this->getApiMock();
		$api->expects( $this->never() )
			->method( 'get' );

		$api->all( [ 'state' => 'invalid' ] );
	}

	/**
	 * Test retrieving all invoices with `'state' => 'draft`.
	 */
	public function testAllState() {
		$response      = $this->getFixture( 'invoices' );
		$expectedArray = $response['invoices'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices', [ 'state' => 'draft' ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'state' => 'draft' ] ) );
	}

	/**
	 * Test retrieving single invoice.
	 */
	public function testShow() {
		$invoiceId = 13150378;

		$expectedArray = $this->getFixture( 'invoice-' . $invoiceId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/invoices/' . $invoiceId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $invoiceId ) );
	}

	/**
	 * Test creating new invoice with no client ID.
	 *
	 * @expectedException \Required\Harvest\Exception\MissingArgumentException
	 */
	public function testCreateMissingClientId() {
		$api = $this->getApiMock();

		$data = [
			'subject'    => 'ABC Project Quote',
			'due_date'   => '2017-07-27',
			'line_items' => [
				[
					'kind'        => 'Service',
					'description' => 'ABC Project',
					'unit_price'  => 5000.0,
				],
			],
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new invoice with invalid client ID.
	 *
	 * @expectedException \Required\Harvest\Exception\InvalidArgumentException
	 */
	public function testCreateInvalidClientId() {
		$api = $this->getApiMock();

		$data = [
			'client_id'  => 0,
			'subject'    => 'ABC Project Quote',
			'due_date'   => '2017-07-27',
			'line_items' => [
				[
					'kind'        => 'Service',
					'description' => 'ABC Project',
					'unit_price'  => 5000.0,
				],
			],
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$api->create( $data );
	}

	/**
	 * Test creating new invoice.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'invoice-13150453' );

		$data = [
			'client_id'  => 5735774,
			'subject'    => 'ABC Project Quote',
			'due_date'   => '2017-07-27',
			'line_items' => [
				[
					'kind'        => 'Service',
					'description' => 'ABC Project',
					'unit_price'  => 5000.0,
				],
			],
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/invoices', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating invoice.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'invoice-13150453' );

		$invoiceId = 13150453;
		$data      = [
			'purchase_order' => '2345',
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/invoices/' . $invoiceId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $invoiceId, $data ) );
	}

	/**
	 * Test deleting invoice.
	 */
	public function testDelete() {
		$invoiceId = 13150453;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/invoices/' . $invoiceId );

		$api->remove( $invoiceId );
	}

	/**
	 * Test marking an invoice as sent.
	 */
	public function testSend() {
		$invoiceId     = 13150403;
		$expectedArray = $this->getFixture( 'invoice-message-27835326' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/invoices/' . $invoiceId . '/messages', [ 'event_type' => 'send' ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->send( $invoiceId ) );
	}

	/**
	 * Test marking an invoice as closed.
	 */
	public function testClose() {
		$invoiceId     = 13150403;
		$expectedArray = $this->getFixture( 'invoice-message-27835326' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/invoices/' . $invoiceId . '/messages', [ 'event_type' => 'close' ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->close( $invoiceId ) );
	}

	/**
	 * Test reopening an invoice.
	 */
	public function testReopen() {
		$invoiceId     = 13150403;
		$expectedArray = $this->getFixture( 'invoice-message-27835327' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/invoices/' . $invoiceId . '/messages', [ 'event_type' => 're-open' ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->reopen( $invoiceId ) );
	}

	/**
	 * Test reopening an invoice.
	 */
	public function testDraft() {
		$invoiceId     = 13150403;
		$expectedArray = $this->getFixture( 'invoice-message-27835328' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/invoices/' . $invoiceId . '/messages', [ 'event_type' => 'draft' ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->draft( $invoiceId ) );
	}
}
