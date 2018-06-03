<?php
/**
 * ResponseMediator class.
 */

namespace Required\Harvest\HttpClient\Message;

use Psr\Http\Message\ResponseInterface;

/**
 * Mediator for API responses.
 */
class ResponseMediator {

	/**
	 * Gets the body of the API response and decodes based on the content type.
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response The API response.
	 * @return array|string
	 */
	public static function getContent( ResponseInterface $response ) {
		$body = $response->getBody()->__toString();

		if ( 0 === strpos( $response->getHeaderLine( 'Content-Type' ), 'application/json' ) ) {
			$content = json_decode( $body, true );

			if ( JSON_ERROR_NONE === json_last_error() ) {
				return $content;
			}
		}

		return $body;
	}

	/**
	 * Gets the pagination parameters of the response.
	 *
	 * @link https://help.getharvest.com/api-v2/introduction/overview/pagination/
	 *
	 * @param \Psr\Http\Message\ResponseInterface $response The API response.
	 * @return array|null
	 */
	public static function getPagination( ResponseInterface $response ): ?array {
		return [];
	}
}
