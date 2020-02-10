<?php
/**
 * CurrentCompany class.
 */

namespace Required\Harvest\Api;

use Http\Client\Exception;

/**
 * API client for company endpoint.
 *
 * @link https://help.getharvest.com/api-v2/company-api/company/company/
 */
class CurrentCompany extends AbstractApi implements CurrentCompanyInterface {


	/**
	 * Retrieves the company for the currently authenticated user.
	 *
	 * @return array|string
	 * @throws Exception
	 */
	public function show() {
		return $this->get( '/company' );
	}
}
