<?php
/**
 * ExpensesTest class.
 */

namespace Required\Harvest\Tests\Api;

use DateTime;
use DateTimeZone;
use Required\Harvest\Api\Expenses;

/**
 * Tests for expenses endpoint.
 */
class ExpensesTest extends TestCase {

	/**
	 * Returns the class name the test case is for.
	 *
	 * @return string Class name.
	 */
	protected function getApiClass(): string {
		return Expenses::class;
	}

	/**
	 * Test retrieving all expenses.
	 */
	public function testAll() {
		$response      = $this->getFixture( 'expenses' );
		$expectedArray = $response['expenses'];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses' )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all() );
	}

	/**
	 * Test retrieving all expenses with invalid response.
	 */
	public function testAllWithInvalidResponse() {
		$response = [];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses' )
			->will( $this->returnValue( $response ) );

		$this->expectException( \Required\Harvest\Exception\RuntimeException::class );

		$api->all();
	}

	/**
	 * Test retrieving all expenses with `'updated_since' => DateTime`.
	 */
	public function testAllUpdatedSinceWithDateTime() {
		$response      = $this->getFixture( 'expenses' );
		$expectedArray = $response['expenses'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses', [ 'updated_since' => $updatedSince->format( DateTime::ATOM ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all expenses with `'updated_since' => 2017-06-26 00:00`.
	 */
	public function testAllUpdatedSinceWithString() {
		$response      = $this->getFixture( 'expenses' );
		$expectedArray = $response['expenses'];

		$updatedSince = '2017-06-26 00:00';

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses', [ 'updated_since' => $updatedSince ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'updated_since' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all expenses with `'from' => DateTime`.
	 */
	public function testAllFromWithDateTime() {
		$response      = $this->getFixture( 'expenses' );
		$expectedArray = $response['expenses'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses', [ 'from' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'from' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving all expenses with `'to' => DateTime`.
	 */
	public function testAllToWithDateTime() {
		$response      = $this->getFixture( 'expenses' );
		$expectedArray = $response['expenses'];

		$updatedSince = new DateTime( '2017-06-26 00:00:00', new DateTimeZone( 'Europe/Zurich' ) );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses', [ 'to' => $updatedSince->format( 'Y-m-d' ) ] )
			->will( $this->returnValue( $response ) );

		$this->assertEquals( $expectedArray, $api->all( [ 'to' => $updatedSince ] ) );
	}

	/**
	 * Test retrieving single expense.
	 */
	public function testShow() {
		$expenseId = 15296442;

		$expectedArray = $this->getFixture( 'expense-' . $expenseId );

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'get' )
			->with( '/expenses/' . $expenseId )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->show( $expenseId ) );
	}

	/**
	 * Test creating new expense with no project ID.
	 */
	public function testCreateMissingProjectId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'             => 1782959,
			'expense_category_id' => 4195926,
			'spent_date'          => '2017-03-01',
			'total_cost'          => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense with invalid client ID.
	 */
	public function testCreateInvalidProjectId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'             => 1782959,
			'project_id'          => 0,
			'expense_category_id' => 4195926,
			'spent_date'          => '2017-03-01',
			'total_cost'          => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense with no expense category ID.
	 */
	public function testCreateMissingExpenseCategoryId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'    => 1782959,
			'project_id' => 14308069,
			'spent_date' => '2017-03-01',
			'total_cost' => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense with invalid expense category ID.
	 */
	public function testCreateInvalidExpenseCategoryId() {
		$api = $this->getApiMock();

		$data = [
			'user_id'             => 1782959,
			'project_id'          => 14308069,
			'expense_category_id' => 0,
			'spent_date'          => '2017-03-01',
			'total_cost'          => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense with no spent date.
	 */
	public function testCreateMissingSpentDate() {
		$api = $this->getApiMock();

		$data = [
			'user_id'             => 1782959,
			'project_id'          => 14308069,
			'expense_category_id' => 4195926,
			'total_cost'          => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\MissingArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense with invalid spent date.
	 */
	public function testCreateInvalidSpentDate() {
		$api = $this->getApiMock();

		$data = [
			'user_id'             => 1782959,
			'project_id'          => 14308069,
			'expense_category_id' => 4195926,
			'spent_date'          => 0,
			'total_cost'          => 13.59,
		];

		$api->expects( $this->never() )
			->method( 'post' );

		$this->expectException( \Required\Harvest\Exception\InvalidArgumentException::class );
		$api->create( $data );
	}

	/**
	 * Test creating new expense.
	 */
	public function testCreateNew() {
		$expectedArray = $this->getFixture( 'expense-15297032' );

		$data = [
			'user_id'             => 1782959,
			'project_id'          => 14308069,
			'expense_category_id' => 4195926,
			'spent_date'          => '2017-03-01',
			'total_cost'          => 13.59,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'post' )
			->with( '/expenses', $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->create( $data ) );
	}

	/**
	 * Test updating expense.
	 */
	public function testUpdate() {
		$expectedArray = $this->getFixture( 'expense-15297032' );

		$expenseId = 15297032;
		$data      = [
			'billable' => false,
		];

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'patch' )
			->with( '/expenses/' . $expenseId, $data )
			->will( $this->returnValue( $expectedArray ) );

		$this->assertEquals( $expectedArray, $api->update( $expenseId, $data ) );
	}

	/**
	 * Test deleting expense.
	 */
	public function testDelete() {
		$expenseId = 15297032;

		$api = $this->getApiMock();
		$api->expects( $this->once() )
			->method( 'delete' )
			->with( '/expenses/' . $expenseId );

		$api->remove( $expenseId );
	}
}
