<?php

class APITest extends TestCase {

	/**
	 * A basic functional test example.
	 *
	 * @return void
	 */
	public function testFrontPage()
	{
		$crawler = $this->client->request('GET', '/');

		$this->assertTrue($this->client->getResponse()->isOk());

		$this->assertCount(1, $crawler->filter('h1:contains("Hello World!")'));
	}

}