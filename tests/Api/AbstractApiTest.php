<?php
/**
 * AbstractApiTest class.
 */

namespace Required\Harvest\Tests\Api;

use GuzzleHttp\Psr7\Response;
use Http\Client\Common\HttpMethodsClient;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use ReflectionMethod;
use Required\Harvest\Api\AbstractApi;
use function GuzzleHttp\Psr7\stream_for;
use Required\Harvest\Client;

/**
 * Tests for company endpoint.
 */
class AbstractApiTest extends TestCase {

	/**
	 * Test get() request is passed to the HTTP client.
	 */
	public function testGetToClient() {
		$expectedBody = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'get' ] );

		$httpClient->expects( $this->any() )
			->method( 'get' )
			->with( '/path?param=param-value', [ 'header' => 'header-value' ] )
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'get' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Test that get() request is passed to the HTTP client with pagination parameters.
	 */
	public function testGetWithPaginationParamsToClient() {
		$expectedPage    = 1;
		$expectedPerPage = 10;
		$expectedBody    = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'get' ] );

		$httpClient->expects( $this->any() )
			->method( 'get' )
			->with( "/path?page={$expectedPage}&per_page={$expectedPerPage}" )
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$api->setPage( $expectedPage );
		$api->setPerPage( $expectedPerPage );

		$this->assertSame( $expectedPage, $api->getPage() );
		$this->assertSame( $expectedPerPage, $api->getPerPage() );

		$actual = $this->getMethod( $api, 'get' )
			->invokeArgs( $api, [
				'/path',
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Test head() request is passed to the HTTP client.
	 */
	public function testHeadToClient() {
		$expectedBody = null;

		$httpClient = $this->getHttpMethodsMock( [ 'head' ] );

		$httpClient->expects( $this->any() )
			->method( 'head' )
			->with( '/path?param=param-value', [ 'header' => 'header-value' ] )
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'head' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertInstanceOf( ResponseInterface::class, $actual );
	}

	/**
	 * Test post() request is passed to the HTTP client.
	 */
	public function testPostToClient() {
		$expectedBody = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'post' ] );

		$httpClient->expects( $this->any() )
			->method( 'post' )
			->with(
				'/path',
				[
					'header'       => 'header-value',
					'Content-Type' => 'application/json',
				],
				json_encode( [ 'param' => 'param-value' ] )
			)
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'post' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Test patch() request is passed to the HTTP client.
	 */
	public function testPatchToClient() {
		$expectedBody = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'patch' ] );

		$httpClient->expects( $this->any() )
			->method( 'patch' )
			->with(
				'/path',
				[
					'header'       => 'header-value',
					'Content-Type' => 'application/json',
				],
				json_encode( [ 'param' => 'param-value' ] )
			)
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'patch' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Test put() request is passed to the HTTP client.
	 */
	public function testPutToClient() {
		$expectedBody = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'put' ] );

		$httpClient->expects( $this->any() )
			->method( 'put' )
			->with(
				'/path',
				[
					'header'       => 'header-value',
					'Content-Type' => 'application/json',
				],
				json_encode( [ 'param' => 'param-value' ] )
			)
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'put' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Test delete() request is passed to the HTTP client.
	 */
	public function testDeleteToClient() {
		$expectedBody = [ 'body' ];

		$httpClient = $this->getHttpMethodsMock( [ 'delete' ] );

		$httpClient->expects( $this->any() )
			->method( 'delete' )
			->with(
				'/path',
				[
					'header'       => 'header-value',
					'Content-Type' => 'application/json',
				],
				json_encode( [ 'param' => 'param-value' ] )
			)
			->will( $this->returnValue( $this->getPsr7Response( $expectedBody ) ) );

		$client = $this->getMockBuilder( Client::class )
			->setMethods( [ 'getHttpClient' ] )
			->getMock();

		$client->expects( $this->any() )
			->method( 'getHttpClient' )
			->willReturn( $httpClient );

		$api = $this->getAbstractApiObject( $client );

		$actual = $this->getMethod( $api, 'delete' )
			->invokeArgs( $api, [
				'/path',
				[
					'param' => 'param-value',
				],
				[
					'header' => 'header-value',
				],
			] );

		$this->assertEquals( $expectedBody, $actual );
	}

	/**
	 * Returns a mock of the API class.
	 *
	 * @param $client
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getAbstractApiObject( $client ) {
		return $this->getMockBuilder( AbstractApi::class )
			->setMethods( null )
			->setConstructorArgs( [ $client ] )
			->getMock();
	}

	/**
	 * Returns a HttpMethods client mock.
	 *
	 * @param array $methods
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getHttpMethodsMock( array $methods = [] ) {
		$methods = array_merge( [ 'sendRequest' ], $methods );
		$mock    = $this->getMockBuilder( HttpMethodsClient::class )
			->disableOriginalConstructor()
			->setMethods( $methods )
			->getMock();

		$mock->expects( $this->any() )
			->method( 'sendRequest' );

		return $mock;
	}

	/**
	 * Returns a response with JSON encoded body.
	 *
	 * @param mixed $body Body of the response.
	 * @return \GuzzleHttp\Psr7\Response
	 */
	private function getPsr7Response( $body ) {
		return new Response(
			200,
			[
				'Content-Type' => 'application/json',
			],
			stream_for( null === $body ? null : json_encode( $body ) )
		);
	}


	/**
	 * Makes a method public accessible.
	 *
	 * @param object $object Instance.
	 * @param string $methodName Method to make public.
	 * @return ReflectionMethod
	 */
	protected function getMethod( $object, $methodName ): ReflectionMethod {
		$method = new ReflectionMethod( $object, $methodName );
		$method->setAccessible( true );

		return $method;
	}
}
