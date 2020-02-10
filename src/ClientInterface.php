<?php

namespace Required\Harvest;

use Http\Client\Common\HttpMethodsClientInterface;
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
use Required\Harvest\Exception\InvalidArgumentException;

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
interface ClientInterface
{
    /**
     * Authenticates a user for all next requests.
     *
     * @link https://help.getharvest.com/api-v2/authentication-api/authentication/authentication/
     *
     * @param string $accountId The Harvest account ID.
     * @param string $accessToken The personal access token.
     */
    public function authenticate(string $accountId, string $accessToken): void;

    /**
     * Retrieves the API interface for an endpoint name.
     *
     * @param string $name The endpoint name.
     * @return ApiInterface The API interface.
     * @throws InvalidArgumentException
     *
     */
    public function api($name): ApiInterface;

    /**
     * Retrieves the HTTP client.
     *
     * @return HttpMethodsClientInterface
     */
    public function getHttpClient(): HttpMethodsClientInterface;
}
