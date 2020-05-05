<?php

namespace Required\Harvest;

use Http\Client\Common\HttpMethodsClientInterface;
use Required\Harvest\Api\ApiInterface;

/**
 * The PHP client for consuming the Harvest REST API.
 *
 * @method \Required\Harvest\Api\ClientsInterface clients()
 * @method \Required\Harvest\Api\ContactsInterface contacts()
 * @method \Required\Harvest\Api\CurrentUserInterface currentUser()
 * @method \Required\Harvest\Api\CurrentCompanyInterface currentCompany()
 * @method \Required\Harvest\Api\EstimateItemCategoriesInterface estimateItemCategories()
 * @method \Required\Harvest\Api\EstimatesInterface estimates()
 * @method \Required\Harvest\Api\ExpenseCategoriesInterface expenseCategories()
 * @method \Required\Harvest\Api\ExpensesInterface expenses()
 * @method \Required\Harvest\Api\InvoiceItemCategoriesInterface invoiceItemCategories()
 * @method \Required\Harvest\Api\InvoicesInterface invoices()
 * @method \Required\Harvest\Api\ProjectsInterface projects()
 * @method \Required\Harvest\Api\RolesInterface roles()
 * @method \Required\Harvest\Api\TaskAssignmentsInterface taskAssignments()
 * @method \Required\Harvest\Api\TasksInterface tasks()
 * @method \Required\Harvest\Api\TimeEntriesInterface timeEntries()
 * @method \Required\Harvest\Api\UserAssignmentsInterface userAssignments()
 * @method \Required\Harvest\Api\UsersInterface users()
 */
interface ClientInterface {

	/**
	 * Authenticates a user for all next requests.
	 *
	 * @link https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/
	 *
	 * @param string $accountId The Harvest account ID.
	 * @param string $accessToken The personal access token.
	 */
	public function authenticate( string $accountId, string $accessToken ): void;

	/**
	 * Retrieves the API interface for an endpoint name.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param string $name The endpoint name.
	 * @return \Required\Harvest\Api\ApiInterface The API interface.
	 */
	public function api( $name ): ApiInterface;

	/**
	 * Retrieves the HTTP client.
	 *
	 * @return \Http\Client\Common\HttpMethodsClientInterface
	 */
	public function getHttpClient(): HttpMethodsClientInterface;
}
