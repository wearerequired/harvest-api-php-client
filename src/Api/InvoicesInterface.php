<?php

namespace Required\Harvest\Api;

use Lafiel\Required\Harvest\Api\Invoice\PaymentInterface;
use Required\Harvest\Api\Invoice\MessagesInterface;

/**
 * API client for invoices endpoint.
 *
 * @link https://help.getharvest.com/api-v2/invoices-api/invoices/invoices/
 */
interface InvoicesInterface {

	/**
	 * Retrieves a list of invoices.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of invoices. Default empty array.
	 *
	 *     @type int $client_id                 Only return invoices belonging to the client with the given ID.
	 *     @type DateTime|string $updated_since Only return invoices that have been updated since the given
	 *                                          date and time.
	 *     @type DateTime|string $from          Only return invoices with a `issue_date` on or after the given date.
	 *     @type DateTime|string $to            Only return invoices with a `issue_date` on or after the given date.
	 *     @type string $state                  Only return invoices with a `state` matching the value provided.
	 *                                          Options: 'draft', 'sent', 'accepted', or 'declined'.
	 * }
	 * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the invoice with the given ID.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function show( int $invoiceId );

	/**
	 * Creates a new invoice object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new invoice object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific invoice by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * TODO: Consider creating an interface for managing invoice line items, see https://help.getharvest.com/api-v2/invoices-api/invoices/invoices/#create-an-invoice-line-item
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $invoiceId, array $parameters );

	/**
	 * Deletes an invoice.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function remove( int $invoiceId );

	/**
	 * Marks a draft invoice as sent.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function send( int $invoiceId );

	/**
	 * Marks an open invoice as closed.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function close( int $invoiceId );

	/**
	 * Re-opens a closed invoice.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function reopen( int $invoiceId );

	/**
	 * Marks an open invoice as a draft.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @return array|string
	 */
	public function draft( int $invoiceId );

	/**
	 * Gets the authenticated user's project assignments.
	 *
	 * @return \Lafiel\Required\Harvest\Api\Invoice\PaymentInterface ;
	 */
	public function payments(): PaymentInterface;

	/**
	 * Gets a Estimate's messages.
	 *
	 * @return \Required\Harvest\Api\Invoice\MessagesInterface
	 */
	public function messages(): MessagesInterface;
}
