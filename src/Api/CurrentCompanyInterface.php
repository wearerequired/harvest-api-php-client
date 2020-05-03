<?php

namespace Required\Harvest\Api;

/**
 * API client for company endpoint.
 *
 * @link https://help.getharvest.com/api-v2/company-api/company/company/
 */
interface CurrentCompanyInterface {

	/**
	 * Retrieves the company for the currently authenticated user.
	 *
	 * @return array|string
	 */
	public function show();
}
