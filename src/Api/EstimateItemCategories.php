<?php
/**
 * EstimateItemCategories class.
 */

namespace Required\Harvest\Api;

use DateTime;

/**
 * API client for estimate item categories endpoint.
 *
 * @link https://help.getharvest.com/api-v2/estimates-api/estimates/estimate-item-categories/
 */
class EstimateItemCategories extends AbstractApi implements EstimateItemCategoriesInterface {


	/**
	 * Retrieves a list of estimate item categories.
	 *
	 * @param array $parameters {
	 *     Optional. Parameters for filtering the list of estimate item categories. Default empty array.
	 *
	 *     @type DateTime|string $updated_since  Only return estimate item categories that have been updated since
	 *                                           the given date and time.
	 * }
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function all( array $parameters = [] ) {
		if ( isset( $parameters['updated_since'] ) && $parameters['updated_since'] instanceof DateTime ) {
			$parameters['updated_since'] = $parameters['updated_since']->format( DateTime::ATOM );
		}

		$result = $this->get( '/estimate_item_categories', $parameters );
		if ( ! isset( $result['estimate_item_categories'] ) || ! \is_array( $result['estimate_item_categories'] ) ) {
			throw new \Required\Harvest\Exception\RuntimeException( 'Unexpected result.' );
		}

		return $result['estimate_item_categories'];
	}

	/**
	 * Retrieves the estimate item category with the given ID.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function show( int $estimateItemCategoryId ) {
		return $this->get( '/estimate_item_categories/' . rawurlencode( $estimateItemCategoryId ) );
	}

	/**
	 * Creates a new estimate item category object.
	 *
	 * @throws \Http\Client\Exception
	 * @throws \Required\Harvest\Exception\MissingArgumentException
	 * @throws \Required\Harvest\Exception\InvalidArgumentException
	 *
	 * @param array $parameters The parameters of the new estimate item category object.
	 * @return array|string
	 */
	public function create( array $parameters ) {
		if ( ! isset( $parameters['name'] ) ) {
			throw new \Required\Harvest\Exception\MissingArgumentException( 'name' );
		}

		if ( ! \is_string( $parameters['name'] ) || empty( trim( $parameters['name'] ) ) ) {
			throw new \Required\Harvest\Exception\InvalidArgumentException( 'The "name" parameter must be a non-empty string.' );
		}

		return $this->post( '/estimate_item_categories', $parameters );
	}

	/**
	 * Updates the specific estimate item category by setting the values of the parameters passed.
	 *
	 * Any parameters not provided will be left unchanged.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @param array $parameters
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function update( int $estimateItemCategoryId, array $parameters ) {
		return $this->patch( '/estimate_item_categories/' . rawurlencode( $estimateItemCategoryId ), $parameters );
	}

	/**
	 * Deletes an estimate item category.
	 *
	 * @param int $estimateItemCategoryId The ID of the estimate item category.
	 * @return array|string
	 * @throws \Http\Client\Exception
	 */
	public function remove( int $estimateItemCategoryId ) {
		return $this->delete( '/estimate_item_categories/' . rawurlencode( $estimateItemCategoryId ) );
	}
}
