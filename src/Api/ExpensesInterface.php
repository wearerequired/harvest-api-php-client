<?php

namespace Required\Harvest\Api;

/**
 * API client for expenses endpoint.
 *
 * @link https://help.getharvest.com/api-v2/expenses-api/expenses/expenses/
 */
interface ExpensesInterface {

	/**
	 * Retrieves a list of expenses.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of expenses. Default empty array.
	 *
	 *     @type int $user_id                   Only return expenses belonging to the user with the given ID.
	 *     @type int $client_id                 Only return expenses belonging to the client with the given ID.
	 *     @type int $project_id                Only return expenses belonging to the project with the given ID.
	 *     @type bool $is_billed                Pass `true` to only return expenses that have been invoiced and
	 *                                          `false` to return expenses that have not been invoiced.
	 *     @type DateTime|string $updated_since Only return expenses that have been updated since the given
	 *                                          date and time.
	 *     @type DateTime|string $from          Only return expenses with a `spent_date` on or after the given date.
	 *     @type DateTime|string $to            Only return expenses with a `spent_date` on or after the given date.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the expense with the given ID.
	 *
	 * @param int $expenseId The ID of the expense.
	 * @return array|string
	 */
	public function show( int $expenseId );

	/**
	 * Creates a new expense object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new expense object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific expense by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $expenseId The ID of the expense.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $expenseId, array $parameters );

	/**
	 * Deletes an expense.
	 *
	 * @param int $expenseId The ID of the expense.
	 * @return array|string
	 */
	public function remove( int $expenseId );
}
