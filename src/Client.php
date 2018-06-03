<?php
/**
 * Client class.
 */

namespace Required\Harvest;

use Http\Client\Common\HttpMethodsClient;
use Http\Client\Common\Plugin;
use Http\Discovery\UriFactoryDiscovery;
use Required\Harvest\Api\ApiInterface;
use Required\Harvest\Exception\BadMethodCallException;
use Required\Harvest\Exception\InvalidArgumentException;
use Required\Harvest\HttpClient\Builder;
use Required\Harvest\HttpClient\Plugin\HarvestAuthentication;
use Required\Harvest\HttpClient\Plugin\ResponseExceptionThrower;

/**
 * The PHP client for consuming the Harvest REST API.
 *
 * @method \Required\Harvest\Api\Clients clients()
 * @method \Required\Harvest\Api\Contacts contacts()
 * @method \Required\Harvest\Api\CurrentUser currentUser()
 * @method \Required\Harvest\Api\CurrentCompany currentCompany()
 * @method \Required\Harvest\Api\TimeEntries timeEntries()
 */
class Client {

	/**
	 * The builder for HTTP clients.
	 *
	 * @var \Required\Harvest\HttpClient\Builder
	 */
	protected $httpClientBuilder;

	/**
	 * Instantiate a new Harvest API client.
	 *
	 * @param \Required\Harvest\HttpClient\Builder|null $httpClientBuilder
	 */
	public function __construct( Builder $httpClientBuilder = null ) {
		$this->httpClientBuilder = $httpClientBuilder ?: new Builder();

		$this->httpClientBuilder->addPlugin( new ResponseExceptionThrower() );
		$this->httpClientBuilder->addPlugin( new Plugin\RedirectPlugin() );
		$this->httpClientBuilder->addPlugin( new Plugin\BaseUriPlugin( UriFactoryDiscovery::find()->createUri( 'https://api.harvestapp.com/v2' ) ) );
		$this->httpClientBuilder->addPlugin( new Plugin\HeaderDefaultsPlugin( [
			'User-Agent' => 'harvest-api-php-client (https://github.com/wearerequired/harvest-api-php-client)',
		] ) );
	}

	/**
	 * Authenticates a user for all next requests.
	 *
	 * @link https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/
	 *
	 * @param string $accountId   The Harvest account ID.
	 * @param string $accessToken The personal access token.
	 */
	public function authenticate( string $accountId, string $accessToken ): void {
		$this->getHttpClientBuilder()->removePlugin( HarvestAuthentication::class );
		$this->getHttpClientBuilder()->addPlugin( new HarvestAuthentication( $accountId, $accessToken ) );
	}

	/**
	 * Retrieves the API interface for an endpoint name.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param string $name The endpoint name.
	 * @return \Required\Harvest\Api\ApiInterface The API interface.
	 */
	public function api( $name ): ApiInterface {
		switch ( $name ) {
			case 'clients':
				return new Api\Clients( $this );
			case 'contacts':
				return new Api\Contacts( $this );
			case 'me':
			case 'currentUser':
				return new Api\CurrentUser( $this );
				break;
			case 'currentCompany':
				return new Api\CurrentCompany( $this );
				break;
			case 'timeEntries':
				return new Api\TimeEntries( $this );
				break;
			default:
				throw new InvalidArgumentException( sprintf( 'Undefined api instance called: "%s"', $name ) );
		}
	}

	/**
	 * Magic method to allow retrieving API interfaces by their name.
	 *
	 * @throws \Required\Harvest\Exception\BadMethodCallException
	 *
	 * @param string $name      The name of the method being called.
	 * @param array  $arguments The arguments passed to the method.
	 * @return \Required\Harvest\Api\ApiInterface The API interface.
	 */
	public function __call( string $name, array $arguments = [] ): ApiInterface {
		try {
			return $this->api( $name );
		} catch ( InvalidArgumentException $e ) {
			throw new BadMethodCallException( sprintf( 'Undefined method called: "%s"', $name ) );
		}
	}

	/**
	 * Retrieves the HTTP client.
	 *
	 * @return \Http\Client\Common\HttpMethodsClient
	 */
	public function getHttpClient(): HttpMethodsClient {
		return $this->getHttpClientBuilder()->getHttpClient();
	}

	/**
	 * Retrieves the builder for HTTP clients.
	 *
	 * @return \Required\Harvest\HttpClient\Builder
	 */
	protected function getHttpClientBuilder(): Builder {
		return $this->httpClientBuilder;
	}
}
