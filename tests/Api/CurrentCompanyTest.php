<?php
/**
 * CurrentCompanyTest class.
 */

namespace Required\Harvest\Tests\Api;

use Required\Harvest\Api\CurrentCompany;

/**
 * Tests for company endpoint.
 */
class CurrentCompanyTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return CurrentCompany::class;
	}

	/**
	 * Test retrieving the current company.
	 */
	public function testShow() {
		$expectedArray = $this->getFixture( 'current-company' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/company' )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show() );
	}
}
