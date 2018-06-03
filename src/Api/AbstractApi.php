<?php
/**
 * AbstractApi class.
 */

namespace Required\Harvest\Api;

use Psr\Http\Message\ResponseInterface;
use Required\Harvest\Client;
use Required\Harvest\HttpClient\Message\ResponseMediator;

/**
 * Abstract class for Api classes.
 */
abstract class AbstractApi implements ApiInterface {

	/**
	 * The client.
	 *
	 * @var \Required\Harvest\Client
	 */
	protected $client;

	/**
	 * The requested page.
	 *
	 * @var null|int
	 */
	private $page;

	/**
	 * Number of items per page.
	 *
	 * @var null|int
	 */
	protected $perPage;

	/**
	 * Constructor.
	 *
	 * @param \Required\Harvest\Client $client
	 */
	public function __construct( Client $client ) {
		$this->client = $client;
	}

	/**
	 * Retrieves requested page number.
	 *
	 * @return null|int
	 */
	public function getPage(): ?int {
		return $this->page;
	}

	/**
	 * Sets requested page number.
	 *
	 * @param null|int $page The requested page.
	 * @return \Required\Harvest\Api\AbstractApi The current API instance
	 */
	public function setPage( $page ): AbstractApi {
		$this->page = ( null === $page ? $page : (int) $page );

		return $this;
	}

	/**
	 * Retrieves number of items per page.
	 *
	 * @return null|int
	 */
	public function getPerPage(): ?int {
		return $this->perPage;
	}

	/**
	 * Sets number of items per page.
	 *
	 * @param null|int $perPage Number of items per page.
	 * @return \Required\Harvest\Api\AbstractApi The current API instance
	 */
	public function setPerPage( $perPage ): AbstractApi {
		$this->perPage = ( null === $perPage ? $perPage : (int) $perPage );

		return $this;
	}

	/**
	 * Sends a GET request with query parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     GET parameters.
	 * @param array  $requestHeaders Request Headers.
	 * @return array|string
	 */
	protected function get( $path, array $parameters = [], array $requestHeaders = [] ) {
		if ( null !== $this->page && ! isset( $parameters['page'] ) ) {
			$parameters['page'] = $this->page;
		}
		if ( null !== $this->perPage && ! isset( $parameters['per_page'] ) ) {
			$parameters['per_page'] = $this->perPage;
		}

		if ( count( $parameters ) > 0 ) {
			$path .= '?' . http_build_query( $parameters );
		}

		$response = $this->client->getHttpClient()->get( $path, $requestHeaders );

		return ResponseMediator::getContent( $response );
	}

	/**
	 * Sends a HEAD request with query parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     HEAD parameters.
	 * @param array  $requestHeaders Request headers.
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	protected function head( $path, array $parameters = [], array $requestHeaders = [] ): ResponseInterface {
		$response = $this->client->getHttpClient()->head( $path . '?' . http_build_query( $parameters ), $requestHeaders );

		return $response;
	}

	/**
	 * Sends a POST request with JSON-encoded parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     POST parameters to be JSON encoded.
	 * @param array  $requestHeaders Request headers.
	 * @return array|string
	 */
	protected function post( $path, array $parameters = [], array $requestHeaders = [] ) {
		$response = $this->client->getHttpClient()->post(
			$path,
			$requestHeaders,
			$this->createJsonBody( $parameters )
		);

		return ResponseMediator::getContent( $response );
	}

	/**
	 * Sends a PATCH request with JSON-encoded parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     POST parameters to be JSON encoded.
	 * @param array  $requestHeaders Request headers.
	 * @return array|string
	 */
	protected function patch( $path, array $parameters = [], array $requestHeaders = [] ) {
		$response = $this->client->getHttpClient()->patch(
			$path,
			$requestHeaders,
			$this->createJsonBody( $parameters )
		);

		return ResponseMediator::getContent( $response );
	}

	/**
	 * Sends a PUT request with JSON-encoded parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     POST parameters to be JSON encoded.
	 * @param array  $requestHeaders Request headers.
	 * @return array|string
	 */
	protected function put( $path, array $parameters = [], array $requestHeaders = [] ) {
		$response = $this->client->getHttpClient()->put(
			$path,
			$requestHeaders,
			$this->createJsonBody( $parameters )
		);

		return ResponseMediator::getContent( $response );
	}

	/**
	 * Sends a DELETE request with JSON-encoded parameters.
	 *
	 * @param string $path           Request path.
	 * @param array  $parameters     POST parameters to be JSON encoded.
	 * @param array  $requestHeaders Request headers.
	 * @return array|string
	 */
	protected function delete( $path, array $parameters = [], array $requestHeaders = [] ) {
		$response = $this->client->getHttpClient()->delete(
			$path,
			$requestHeaders,
			$this->createJsonBody( $parameters )
		);

		return ResponseMediator::getContent( $response );
	}

	/**
	 * Creates a JSON encoded version of an array of parameters.
	 *
	 * @param array $parameters Request parameters
	 * @return null|string
	 */
	protected function createJsonBody( array $parameters ): ?string {
		return ( count( $parameters ) === 0 ) ? null : json_encode( $parameters, empty( $parameters ) ? JSON_FORCE_OBJECT : 0 );
	}
}
