<?php

namespace Required\Harvest\Api;

/**
 * API client for expense categories endpoint.
 *
 * @link https://help.getharvest.com/api-v2/expenses-api/expenses/expense-categories/
 */
interface ExpenseCategoriesInterface {

	/**
	 * Retrieves a list of expense categories.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of expense categories. Default empty array.
	 *
	 *     @type bool $is_active                Pass `true` to only return active expense categories and `false`
	 *                                          to return inactive expense categories.
	 *     @type int $client_id                 Only return expense categories belonging to the client with the given ID.
	 *     @type DateTime|string $updated_since Only return expense categories that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the expense category with the given ID.
	 *
	 * @param int $expenseCategoryId The ID of the expense category.
	 * @return array|string
	 */
	public function show( int $expenseCategoryId );

	/**
	 * Creates a new expense category object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new expense category object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific expense category by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $expenseCategoryId The ID of the expense category.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $expenseCategoryId, array $parameters );

	/**
	 * Deletes an expense category.
	 *
	 * @param int $expenseCategoryId The ID of the expense category.
	 * @return array|string
	 */
	public function remove( int $expenseCategoryId );
}
