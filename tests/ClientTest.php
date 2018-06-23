<?php
/**
 * ClientTest class.
 */

namespace Required\Harvest\Tests;

use Required\Harvest\Client;

/**
 * Tests the Client class.
 */
class ClientTest extends \PHPUnit\Framework\TestCase {

	public function testHttpClientIsOptional() {
		$client = new Client();
		$this->assertInstanceOf( \Http\Client\HttpClient::class, $client->getHttpClient() );
	}
}
