<?php

class SearchTest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testIsJson()
	{
		$crawler = $this->client->request('GET', '/');
		// $crawler = $this->client->request('GET', '/openapi/eldis/search/');
		$this->assertTrue($this->client->getResponse()->isOk());
		// $this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));

		$crawler = $this->client->request('GET', '/openapi/eldis/search/object');
		$this->assertTrue($this->client->getResponse()->isOk());

		$crawler = $this->client->request('GET', '/openapi/eldis/search/object/parameter');
		$this->assertTrue($this->client->getResponse()->isOk());

		$crawler = $this->client->request('GET', '/openapi/eldis/search/object/parameter/query');
		$this->assertTrue($this->client->getResponse()->isOk());

	}

}