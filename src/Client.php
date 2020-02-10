<?php
/**
 * Client class.
 */

namespace Required\Harvest;

use Http\Client\Common\HttpMethodsClientInterface;
use Http\Client\Common\Plugin;
use Http\Client\HttpClient;
use Http\Discovery\UriFactoryDiscovery;
use Required\Harvest\Api\ApiInterface;
use Required\Harvest\Api\ClientsInterface;
use Required\Harvest\Api\ContactsInterface;
use Required\Harvest\Api\CurrentCompanyInterface;
use Required\Harvest\Api\CurrentUserInterface;
use Required\Harvest\Api\EstimateItemCategoriesInterface;
use Required\Harvest\Api\EstimatesInterface;
use Required\Harvest\Api\ExpenseCategoriesInterface;
use Required\Harvest\Api\ExpensesInterface;
use Required\Harvest\Api\InvoiceItemCategoriesInterface;
use Required\Harvest\Api\InvoicesInterface;
use Required\Harvest\Api\ProjectsInterface;
use Required\Harvest\Api\RolesInterface;
use Required\Harvest\Api\TaskAssignmentsInterface;
use Required\Harvest\Api\TasksInterface;
use Required\Harvest\Api\TimeEntriesInterface;
use Required\Harvest\Api\UserAssignmentsInterface;
use Required\Harvest\Api\UsersInterface;
use Required\Harvest\Exception\BadMethodCallException;
use Required\Harvest\Exception\InvalidArgumentException;
use Required\Harvest\HttpClient\Builder;
use Required\Harvest\HttpClient\Plugin\HarvestAuthentication;
use Required\Harvest\HttpClient\Plugin\ResponseExceptionThrower;

/**
 * The PHP client for consuming the Harvest REST API.
 *
 * @method ClientsInterface clients()
 * @method ContactsInterface contacts()
 * @method CurrentUserInterface currentUser()
 * @method CurrentCompanyInterface currentCompany()
 * @method EstimateItemCategoriesInterface estimateItemCategories()
 * @method EstimatesInterface estimates()
 * @method ExpenseCategoriesInterface expenseCategories()
 * @method ExpensesInterface expenses()
 * @method InvoiceItemCategoriesInterface invoiceItemCategories()
 * @method InvoicesInterface invoices()
 * @method ProjectsInterface projects()
 * @method RolesInterface roles()
 * @method TaskAssignmentsInterface taskAssignments()
 * @method TasksInterface tasks()
 * @method TimeEntriesInterface timeEntries()
 * @method UserAssignmentsInterface userAssignments()
 * @method UsersInterface users()
 */
class Client implements ClientInterface
{

	/**
	 * The builder for HTTP clients.
	 *
	 * @var Builder
	 */
	protected $httpClientBuilder;

	/**
	 * Instantiate a new Harvest API client.
	 *
	 * @param Builder|null $httpClientBuilder
	 */
	public function __construct( Builder $httpClientBuilder = null ) {
		$this->httpClientBuilder = $httpClientBuilder ?: new Builder();

		$this->httpClientBuilder->addPlugin( new ResponseExceptionThrower() );
		$this->httpClientBuilder->addPlugin( new Plugin\RedirectPlugin() );
		$this->httpClientBuilder->addPlugin( new Plugin\BaseUriPlugin( UriFactoryDiscovery::find()->createUri( 'https://api.harvestapp.com/v2' ) ) );
		$this->httpClientBuilder->addPlugin(
			new Plugin\HeaderDefaultsPlugin(
				[
					'User-Agent' => 'harvest-api-php-client (https://github.com/wearerequired/harvest-api-php-client)',
				]
			)
		);
	}

	/**
	 * Creates a Harvest\Client using a HttpClient.
	 *
	 * @param HttpClient $httpClient The HttpClient.
	 * @return Client Harvest API client.
	 */
	public static function createWithHttpClient( HttpClient $httpClient ): ClientInterface {
		$builder = new Builder( $httpClient );
		return new self( $builder );
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
	 * @throws InvalidArgumentException
	 *
	 * @param string $name The endpoint name.
	 * @return ApiInterface The API interface.
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
			case 'currentCompany':
				return new Api\CurrentCompany( $this );
			case 'estimateItemCategories':
				return new Api\EstimateItemCategories( $this );
			case 'estimates':
				return new Api\Estimates( $this );
			case 'expenseCategories':
				return new Api\ExpenseCategories( $this );
			case 'expenses':
				return new Api\Expenses( $this );
			case 'invoiceItemCategories':
				return new Api\InvoiceItemCategories( $this );
			case 'invoices':
				return new Api\Invoices( $this );
			case 'projects':
				return new Api\Projects( $this );
			case 'roles':
				return new Api\Roles( $this );
			case 'taskAssignments':
				return new Api\TaskAssignments( $this );
			case 'tasks':
				return new Api\Tasks( $this );
			case 'timeEntries':
				return new Api\TimeEntries( $this );
			case 'userAssignments':
				return new Api\UserAssignments( $this );
			case 'users':
				return new Api\Users( $this );
			default:
				throw new InvalidArgumentException( sprintf( 'Undefined api instance called: "%s"', $name ) );
		}
	}

	/**
	 * Magic method to allow retrieving API interfaces by their name.
	 *
	 * @throws BadMethodCallException
	 *
	 * @param string $name      The name of the method being called.
	 * @param array  $arguments The arguments passed to the method.
	 * @return ApiInterface The API interface.
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
	 * @return HttpMethodsClientInterface
	 */
	public function getHttpClient(): HttpMethodsClientInterface {
		return $this->getHttpClientBuilder()->getHttpClient();
	}

	/**
	 * Retrieves the builder for HTTP clients.
	 *
	 * @return Builder
	 */
	protected function getHttpClientBuilder(): Builder {
		return $this->httpClientBuilder;
	}
}
