<?php


namespace src\Api;


class PaginatedResult {

	public function __construct( array $items ) {

		$projects = $client->projects();
		$projects->setPerPage( 10 );
		$page = $projects->all();

		while ( $page->hasNextPage() ) {
			foreach ( $page->getItems() as $item ) {
				// Do some stuff here
			}
		}

		foreach ( $page->getItems() as $item ) {
			// Do some stuff here
		}

		$page = $page->getNextPage();

		foreach ( $page->getItems() as $item ) {
			// Do some stuff here
		}

		$page = $page->getPreviousPage(); // ?
	}
}
