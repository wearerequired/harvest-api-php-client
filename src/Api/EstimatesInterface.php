<?php

namespace Required\Harvest\Api;

use Required\Harvest\Api\Estimate\MessagesInterface;

/**
 * API client for estimates endpoint.
 *
 * @link https://help.getharvest.com/api-v2/estimates-api/estimates/estimates/
 */
interface EstimatesInterface {

	/**
	 * Retrieves a list of estimates.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of estimates. Default empty array.
	 *
	 *     @type int $client_id                 Only return estimates belonging to the client with the given ID.
	 *     @type DateTime|string $updated_since Only return estimates that have been updated since the given
	 *                                          date and time.
	 *     @type DateTime|string $from          Only return estimates with a `issue_date` on or after the given date.
	 *     @type DateTime|string $to            Only return estimates with a `issue_date` on or after the given date.
	 *     @type string $state                  Only return estimates with a `state` matching the value provided.
	 *                                          Options: 'draft', 'sent', 'accepted', or 'declined'.
	 * }
	  * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the estimate with the given ID.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function show( int $estimateId );

	/**
	 * Creates a new estimate object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new estimate object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific estimate by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * TODO: Consider creating an interface for managing estimate line items, see https://help.getharvest.com/api-v2/estimates-api/estimates/estimates/#create-an-estimate-line-item
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $estimateId, array $parameters );

	/**
	 * Deletes an estimate.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function remove( int $estimateId );

	/**
	 * Marks a draft estimate as sent.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function send( int $estimateId );

	/**
	 * Marks an open estimate as accepted.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function accept( int $estimateId );

	/**
	 * Marks an open estimate as declined.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function decline( int $estimateId );

	/**
	 * Re-opens a closed estimate
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @return array|string
	 */
	public function reopen( int $estimateId );

	/**
	 * Gets a Estimate's messages.
	 *
	 * @return \Required\Harvest\Api\Estimate\MessagesInterface
	 */
	public function messages(): MessagesInterface;
}
