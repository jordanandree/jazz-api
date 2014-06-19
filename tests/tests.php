<?php

class ResumatorTestCase extends PHPUnit_Framework_TestCase {

  const API_KEY = "YOUR_API_KEY";

  protected function setUp() {
    $this->mock = $this->getMockBuilder('Resumator')
              ->disableOriginalConstructor()
              ->setMethods(array("apiRequest"))
              ->getMock();
    $this->mock->expects($this->any())
         ->method('apiRequest');
  }

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
    $this->mock->getJobs();
  }

  /**
   * @group request_invalid
   */
  public function testRequestInvalid() {
    $this->setExpectedException("Exception");

    $this->mock->getFoo();
  }

  /**
   * @group request_invalid
   */
  public function testInvalidParams() {
    $this->setExpectedException("Exception");

    $this->mock->postJob();
  }

  /**
   * @group activity
   */
  public function testGetActivity() {
    $this->mock->getActivity("activity_id");
  }

  /**
   * @group activity
   */
  public function testGetActivities() {
    $this->mock->getActivities();
  }

  /**
   * @group applicant
   */
  public function testGetApplicant() {
    $this->mock->getApplicant("applicant_id");
  }

  /**
   * @group applicant
   */
  public function testGetApplicants() {
    $this->mock->getApplicants();
  }

  /**
   * @group applicant
   */
  public function testPostApplicant() {
    $this->mock->postApplicant(array(
      "first_name" => "Foo"
    ));
  }

  /**
   * @group applicants2jobs
   */
  public function testGetApplicants2Job() {
    $this->mock->getApplicants2Job("app2job_id");
  }

  /**
   * @group applicants2jobs
   */
  public function testGetApplicants2Jobs() {
    $this->mock->getApplicants2Jobs();
  }

  /**
   * @group applicants2jobs
   */
  public function testPostApplicants2Job() {
    $this->mock->postApplicants2Job(array(
      "applicant_id" => "foo",
      "job_id" => "foo"
    ));
  }

  /**
   * @group categories
   */
  public function testGetCategory() {
    $this->mock->getCategory("category_id");
  }

  /**
   * @group categories
   */
  public function testGetCategories() {
    $this->mock->getCategories();
  }

  /**
   * @group categories
   */
  public function testPostCategory() {
    $this->mock->postCategory(array(
      "name" => "Foo"
    ));
  }

  /**
   * @group categories2applicants
   */
  public function testGetCategories2Applicant() {
    $this->mock->getCategories2Applicant("pro2cat_id");
  }

  /**
   * @group categories2applicants
   */
  public function testGetCategories2Applicants() {
    $this->mock->getCategories2Applicants();
  }

  /**
   * @group categories2applicants
   */
  public function testPostCategories2Applicant() {
    $this->mock->postCategories2Applicant(array(
      "applicant_id" => "foo",
      "category_id" => "foo"
    ));
  }

  /**
   * @group contacts
   */
  public function testGetContact() {
    $this->mock->getContact("contact_id");
  }

  /**
   * @group contacts
   */
  public function testGetContacts() {
    $this->mock->getContacts();
  }

  /**
   * @group files
   */
  public function testGetFile() {
    $this->mock->getFile("file_id");
  }

  /**
   * @group files
   */
  public function testGetFiles() {
    $this->mock->getFiles();
  }

  /**
   * @group files
   */
  public function testPostFile() {
    $this->mock->postFile(array(
      "filename" => "foo"
    ));
  }

  /**
   * @group jobs
   */
  public function testGetJob() {
    $this->mock->getJob("job_id");
  }

  /**
   * @group jobs
   */
  public function testPostJob() {
    $this->mock->postJob(array(
      "title" => "Open Job"
    ));
  }

  /**
   * @group jobs
   */
  public function testGetJobs() {
    $this->mock->getJobs();
  }

  /**
   * @group notes
   */
  public function testPostNote() {
    $this->mock->postNote(array(
      "applicant_id" => "foo"
    ));
  }

  /**
   * @group questionnaire_answers
   */
  public function testQuestionnaireAnswers() {
    $this->mock->getQuestionnaireAnswers();
  }

  /**
   * @group questionnaire_answers
   */
  public function testPostQuestionnaireAnswer() {
    $this->mock->postQuestionnaireAnswer(array(
      "applicant_id" => "foo",
      "questionnaire_id" => "foo"
    ));
  }

  /**
   * @group questionnaire_questions
   */
  public function testQuestionnaireQuestions() {
    $this->mock->getQuestionnaireQuestions();
  }

  /**
   * @group tasks
   */
  public function testTasks() {
    $this->mock->getTasks();
  }

  /**
   * @group tasks
   */
  public function testTask() {
    $this->mock->getTask("task_id");
  }

  /**
   * @group users
   */
  public function testUsers() {
    $this->mock->getUsers();
  }

  /**
   * @group users
   */
  public function testUser() {
    $this->mock->getUser("user_id");
  }

}