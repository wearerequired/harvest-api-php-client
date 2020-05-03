<?php
/**
 * ClientTest class.
 */

namespace Required\Harvest\Tests;

use Http\Client\HttpClient;
use PHPUnit\Framework\TestCase;
use Required\Harvest\Api;
use Required\Harvest\Client;
use Required\Harvest\HttpClient\Builder;
use Required\Harvest\HttpClient\Plugin\HarvestAuthentication;

/**
 * Tests the Client class.
 */
class ClientTest extends TestCase {

	/**
	 * Test that the contructor doesn't require a HTTP client.
	 */
	public function testHttpClientIsOptionalForConstructor() {
		$client = new Client();
		$this->assertInstanceOf( HttpClient::class, $client->getHttpClient() );
	}

	/**
	 * Test custom HttpClient.
	 */
	public function testPassingHttpClientInterfaceToConstructor() {
		$httpClientMock = $this->getMockBuilder( HttpClient::class )->getMock();
		$client         = Client::createWithHttpClient( $httpClientMock );
		$this->assertInstanceOf( HttpClient::class, $client->getHttpClient() );
	}

	/**
	 * Test client authentication.
	 */
	public function testAuthentication() {
		$accountId   = '123456';
		$accessToken = 'token';

		$builder = $this->getMockBuilder( Builder::class )
			->setMethods( [ 'addPlugin', 'removePlugin' ] )
			->disableOriginalConstructor()
			->getMock();

		$builder->expects( $this->once() )
			->method( 'addPlugin' )
			->with( $this->equalTo( new HarvestAuthentication( $accountId, $accessToken ) ) );

		$builder->expects( $this->once() )
			->method( 'removePlugin' )
			->with( HarvestAuthentication::class );

		$client = $this->getMockBuilder( Client::class )
			->disableOriginalConstructor()
			->setMethods( [ 'getHttpClientBuilder' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClientBuilder' )
			->willReturn( $builder );

		$client->authenticate( $accountId, $accessToken );
	}

	/**
	 * Test retrieving the API interface for an endpoint name.
	 *
	 * @dataProvider getApiClassesProvider
	 *
	 * @param string $name Endpoint name.
	 * @param string $class Class name.
	 */
	public function testReturnOfGetApiInstance( $name, $class ) {
		$client = new Client();
		$this->assertInstanceOf( $class, $client->api( $name ) );
	}

	/**
	 * Test retrieving the API interface for an endpoint name via magic method.
	 *
	 * @dataProvider getApiClassesProvider
	 *
	 * @param string $name Endpoint name.
	 * @param string $class Class name.
	 */
	public function testReturnOfGetMagicApiInstance( $name, $class ) {
		$client = new Client();
		$this->assertInstanceOf( $class, $client->$name() );
	}

	/**
	 * Test throwing an exception for unavailable API interfaces.
	 */
	public function testInvalidGetApiInstance() {
		$client = new Client();
		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$client->api( 'cookies' );
	}

	/**
	 * Test throwing an exception for unavailable API interfaces via magic method.
	 */
	public function testInvalidGetMagicApiInstance() {
		$client = new Client();
		$this->expectException( \Required\Harvest\Exception\BadMethodCallException::class );
		$client->cookies();
	}

	/**
	 * Data provider for API interfaces.
	 *
	 * @return array
	 */
	public function getApiClassesProvider(): array {
		return [
			[ 'clients', Api\Clients::class ],
			[ 'contacts', Api\Contacts::class ],
			[ 'me', Api\CurrentUser::class ],
			[ 'currentUser', Api\CurrentUser::class ],
			[ 'currentCompany', Api\CurrentCompany::class ],
			[ 'estimateItemCategories', Api\EstimateItemCategories::class ],
			[ 'estimates', Api\Estimates::class ],
			[ 'expenseCategories', Api\ExpenseCategories::class ],
			[ 'expenses', Api\Expenses::class ],
			[ 'invoiceItemCategories', Api\InvoiceItemCategories::class ],
			[ 'invoices', Api\Invoices::class ],
			[ 'projects', Api\Projects::class ],
			[ 'roles', Api\Roles::class ],
			[ 'taskAssignments', Api\TaskAssignments::class ],
			[ 'tasks', Api\Tasks::class ],
			[ 'timeEntries', Api\TimeEntries::class ],
			[ 'userAssignments', Api\UserAssignments::class ],
			[ 'users', Api\Users::class ],
		];
	}
}
