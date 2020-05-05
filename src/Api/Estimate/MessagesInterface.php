<?php

namespace Required\Harvest\Api\Estimate;

/**
 * API client for estimate messages endpoint.
 *
 * @link https://help.getharvest.com/api-v2/estimates-api/estimates/estimate-messages/
 */
interface MessagesInterface {

	/**
	 * Retrieves a list of estimate messages for a specific estimate.
	 *
	 * @param int   $estimateId The ID of the estimate.
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of estimate messages. Default empty array.
	 *
	 *     @type DateTime|string $updated_since Only return estimate messages that have been updated since the given
	 *                                          date and time.
	 * }
	 * @return array|string
	 */
	public function all( int $estimateId, array $parameters = [] );

	/**
	 * Retrieves the estimate message with the given ID.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @param int $messageId The ID of the estimate message.
	 * @return array|string
	 */
	public function show( int $estimateId, int $messageId );

	/**
	 * Creates a new estimate message object.
	 *
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @param array $parameters The parameters of the new estimate message object.
	 * @return array|string
	 */
	public function create( int $estimateId, array $parameters );

	/**
	 * Deletes an estimate message.
	 *
	 * @param int $estimateId The ID of the estimate.
	 * @param int $messageId The ID of the estimate message.
	 * @return array|string
	 */
	public function remove( int $estimateId, int $messageId );
}
