<?php

class ResumatorTestCase extends PHPUnit_Framework_TestCase {

  const API_KEY = "YOUR_API_KEY";

  /**
   * @group constructor
   */
  public function testConstructor() {
    $resumator = new Resumator(self::API_KEY);
    $this->assertEquals($resumator->getApiKey(), self::API_KEY,
                        'Expect the API Key to be set.');

    putenv("RESUMATOR_API_KEY=" . self::API_KEY);
    $resumator = new Resumator();
    $this->assertEquals($resumator->getApiKey(), self::API_KEY,
                        'Expect the API Key to be set through env vars.');
    putenv("RESUMATOR_API_KEY=");
  }

  /**
   * @group call
   */
  public function testCall() {
    $resumator = new Resumator();

    $response = $resumator->getJobs();

    $this->assertEquals(gettype($response), "object",
                        'Expect the response to be an object');
  }

}