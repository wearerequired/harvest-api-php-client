<?php

namespace Required\Harvest\Api\Invoice;

/**
 * API client for invoice messages endpoint.
 *
 * @link https://help.getharvest.com/api-v2/invoices-api/invoices/invoice-messages/
 */
interface MessagesInterface {

	/**
	 * Retrieves a list of invoice messages for a specific invoice.
	 *
	 * @param int   $invoiceId  The ID of the invoice.
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of invoice messages. Default empty array.
	 *
	 *     @type DateTime|string $updated_since Only return invoice messages that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( int $invoiceId, array $parameters = [] );

	/**
	 * Retrieves the invoice message with the given ID.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @param int $messageId The ID of the invoice message.
	 * @return array|string
	 */
	public function show( int $invoiceId, int $messageId );

	/**
	 * Creates a new invoice message object.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @param array $parameters The parameters of the new invoice message object.
	 * @return array|string
	 */
	public function create( int $invoiceId, array $parameters );

	/**
	 * Deletes an invoice message.
	 *
	 * @param int $invoiceId The ID of the invoice.
	 * @param int $messageId The ID of the invoice message.
	 * @return array|string
	 */
	public function remove( int $invoiceId, int $messageId );
}
