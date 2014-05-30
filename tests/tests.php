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
   * @group request
   */
  public function testRequest() {
    $mock = $this->getMockBuilder('Resumator')
              ->disableOriginalConstructor()
              ->setMethods(array("apiRequest"))
              ->getMock();

    $mock->expects($this->any())
         ->method('apiRequest');

    $mock->getJobs();
  }

  /**
   * @group request_invalid
   */
  public function testRequestInvalid() {
    $mock = $this->getMockBuilder('Resumator')
              ->disableOriginalConstructor()
              ->setMethods(array("apiRequest"))
              ->getMock();

    $this->setExpectedException("Exception");

    $mock->getFoo();
  }

  /**
   * @group request_single
   */
  public function testRequestSingle() {
    $mock = $this->getMockBuilder('Resumator')
              ->disableOriginalConstructor()
              ->setMethods(array("apiRequest"))
              ->getMock();

    $mock->expects($this->any())
         ->method('apiRequest');

    $mock->getJob("job_1234_1234");
  }

  /**
   * @group request_post
   */
  public function testRequestPost() {
    $mock = $this->getMockBuilder('Resumator')
              ->disableOriginalConstructor()
              ->setMethods(array("apiRequest"))
              ->getMock();

    $mock->postJob(array("title" => "Open Job"));

    $mock->expects($this->any())
         ->method('apiRequest');
  }

}