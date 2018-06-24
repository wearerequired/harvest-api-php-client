<?php
/**
 * TestCase class.
 */

namespace Required\Harvest\Tests\Api;

use Http\Client\HttpClient;
use PHPUnit_Framework_MockObject_MockObject;
use Required\Harvest\Client;

/**
 * Base class for API test cases.
 */
abstract class TestCase extends \PHPUnit\Framework\TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	abstract protected function getApiClass(): string;

	/**
	 * @return \PHPUnit_Framework_MockObject_MockObject
	 */
	protected function getApiMock(): PHPUnit_Framework_MockObject_MockObject {
		$httpClient = $this->getMockBuilder( HttpClient::class )
			->setMethods( [ 'sendRequest' ] )
			->getMock();

		$httpClient->expects( $this->any() )
			->method( 'sendRequest' );

		$client = Client::createWithHttpClient( $httpClient );

		return $this->getMockBuilder( $this->getApiClass() )
			->setMethods( [ 'get', 'post', 'patch', 'delete', 'put', 'head' ] )
			->setConstructorArgs( [ $client ] )
			->getMock();
	}

	/**
	 * Retrieves fixture for an endpoint..
	 *
	 * @param string $name Name of the file without extension.
	 * @return array Fixture data.
	 */
	protected function getFixture( $name ) {
		return json_decode( file_get_contents( dirname( __DIR__ ) . "/fixtures/{$name}.json" ), true );
	}
}
