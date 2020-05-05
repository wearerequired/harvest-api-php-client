<?php

namespace Required\Harvest\Api;

/**
 * API client for estimate item categories endpoint.
 *
 * @link https://help.getharvest.com/api-v2/estimates-api/estimates/estimate-item-categories/
 */
interface EstimateItemCategoriesInterface {

	/**
	 * Retrieves a list of estimate item categories.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of estimate item categories. Default empty array.
	 *
	 *     @type DateTime|string $updated_since Only return estimate item categories that have been updated since
	 *                                          the given date and time.
	 * }
	  * @return array|string
	 */
	public function all( array $parameters = [] );

	/**
	 * Retrieves the estimate item category with the given ID.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @return array|string
	 */
	public function show( int $estimateItemCategoryId );

	/**
	 * Creates a new estimate item category object.
	 *
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new estimate item category object.
	 * @return array|string
	 */
	public function create( array $parameters );

	/**
	 * Updates the specific estimate item category by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @param array $parameters
	 * @return array|string
	 */
	public function update( int $estimateItemCategoryId, array $parameters );

	/**
	 * Deletes an estimate item category.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @return array|string
	 */
	public function remove( int $estimateItemCategoryId );
}
