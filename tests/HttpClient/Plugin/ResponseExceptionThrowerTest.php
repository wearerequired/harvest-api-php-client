<?php
/**
 * ResponseExceptionThrowerTest class.
 */

namespace Required\Harvest\Tests\HttpClient\Plugin;

use GuzzleHttp\Psr7\Response;
use Http\Client\Promise\HttpFulfilledPromise;
use Http\Promise\Promise;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Required\Harvest\Exception\ExceptionInterface;
use Required\Harvest\HttpClient\Plugin\ResponseExceptionThrower;

/**
 * Tests for API response errors.
 */
class ResponseExceptionThrowerTest extends TestCase {

	/**
	 * @param \Psr\Http\Message\ResponseInterface                            $response
	 * @param \Required\Harvest\Exception\ExceptionInterface|\Exception|null $exception
	 *
	 * @dataProvider responseProvider
	 */
	public function testDoHandleRequest( ResponseInterface $response, ?ExceptionInterface $exception = null ) {
		/** @var \Psr\Http\Message\RequestInterface $request */
		$request = $this->getMockForAbstractClass( RequestInterface::class );
		$plugin  = new ResponseExceptionThrower();
		$verify  = function ( RequestInterface $request ) use ( $response ) {
			return new HttpFulfilledPromise( $response );
		};
		$first   = function () {};
		$promise = $plugin->doHandleRequest( $request, $verify, $first );

		if ( $exception ) {
			$this->assertSame( Promise::REJECTED, $promise->getState() );

			$this->expectException( \get_class( $exception ) );
			$this->expectExceptionCode( $exception->getCode() );
			$this->expectExceptionMessage( $exception->getMessage() );
		} else {
			$this->assertSame( Promise::FULFILLED, $promise->getState() );
		}

		$promise->wait();
	}

	/**
	 * @return array
	 */
	public static function responseProvider() {
		return [
			'200 Response' => [
				'response'  => new Response(),
				'exception' => null,
			],
			'400 Response' => [
				'response'  => new Response(
					400,
					[ 'Content-Type' => 'application/json' ],
					'{"message":"Invalid updated_since datetime provided: \"2020-05-03T14:43:13B02:00\""}'
				),
				'exception' => new \Required\Harvest\Exception\ErrorException( 'Invalid updated_since datetime provided: "2020-05-03T14:43:13B02:00"', 400 ),
			],
			'401 Response' => [
				'response'  => new Response(
					401,
					[ 'Content-Type' => 'application/json' ],
					'{"error":"invalid_token","error_description":"The access token provided is expired, revoked, malformed or invalid for other reasons."}'
				),
				'exception' => new \Required\Harvest\Exception\AuthenticationException( 'The access token provided is expired, revoked, malformed or invalid for other reasons.' ),
			],
			'403 Response' => [
				'response'  => new Response( 403, [], '' ),
				'exception' => new \Required\Harvest\Exception\AuthorizationException(),
			],
			'404 Response' => [
				'response'  => new Response(
					404,
					[ 'Content-Type' => 'application/json' ],
					'{"status":404,"error":"Not Found"}'
				),
				'exception' => new \Required\Harvest\Exception\NotFoundException(),
			],
			'422 Response' => [
				'response'  => new Response(
					422,
					[ 'Content-Type' => 'application/json' ],
					'{"message":"Foo"}'
				),
				'exception' => new \Required\Harvest\Exception\ValidationFailedException( 'Foo', 422 ),
			],
			'429 Response' => [
				'response'  => new Response( 429, [], '' ),
				'exception' => new \Required\Harvest\Exception\HarvestApiRateLimitExceedException(),
			],
			'502 Response' => [
				'response'  => new Response( 502, [], '' ),
				'exception' => new \Required\Harvest\Exception\RuntimeException( '""', 502 ),
			],
		];
	}
}
