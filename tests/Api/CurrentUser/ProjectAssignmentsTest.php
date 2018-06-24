<?php
/**
 * ProjectAssignmentsTest class.
 */

namespace Required\Harvest\Tests\Api\CurrentUser;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\CurrentUser\ProjectAssignments;
use Required\Harvest\Tests\Api\TestCase;

/**
 * Tests for current user project assignments endpoint.
 */
class ProjectAssignmentsTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return ProjectAssignments::class;
	}

	/**
	 * Test retrieving all project assignments.
	 */
	public function testAll() {
		$expectedArray = $this->getFixture( 'project-assignments' );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users/me/project_assignments' )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all project assignments with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$expectedArray = $this->getFixture( 'project-assignments' );

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users/me/project_assignments', [ 'updated_since' => $updatedSince->format( 'Y-m-d H:i' ) ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all project assignments with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$expectedArray = $this->getFixture( 'project-assignments' );

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/users/me/project_assignments', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}
}
