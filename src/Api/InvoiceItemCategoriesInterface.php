<?php

namespace Required\Harvest\Api;

/**
 * API client for invoice item categories endpoint.
 *
 * @link https://help.getharvest.com/api-v2/invoices-api/invoices/invoice-item-categories/
 */
interface InvoiceItemCategoriesInterface {

	/**
	 * Retrieves a list of invoice item categories.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of invoice item categories. Default empty array.
	 *
	 *     @type DateTime|string $updated_since Only return invoice item categories that have been updated since
	 *                                          the given date and time.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the invoice item category with the given ID.
	 *
	 * @param int $invoiceItemCategoryId The ID of the invoice item category.
	 * @return array|string
	 */
	public function show( int $invoiceItemCategoryId );

	/**
	 * Creates a new invoice item category object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new invoice item category object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific invoice item category by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $invoiceItemCategoryId The ID of the invoice item category.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $invoiceItemCategoryId, array $parameters );

	/**
	 * Deletes an invoice item category.
	 *
	 * Deleting an invoice item category is only possible if `use_as_service` and `use_as_expense` are both false.
	 *
	 * @param int $invoiceItemCategoryId The ID of the invoice item category.
	 * @return array|string
	 */
	public function remove( int $invoiceItemCategoryId );
}
