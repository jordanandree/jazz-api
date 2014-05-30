<?php


if (!function_exists('curl_init')) {
  throw new Exception('Resumator needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Resumator needs the JSON PHP extension.');
}

use Doctrine\Common\Inflector\Inflector as DoctrineInflector;

class Resumator {

  /**
   * API Base URL
   */
  const API_URL = "https://api.resumatorapi.com/";

  /**
   * API Version
   */
  const API_VERSION = "v1";

  /**
   * Default options for curl.
   *
   * @var array
   */
  public static $CURL_OPTS = array(
    CURLOPT_CONNECTTIMEOUT => 10,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_TIMEOUT        => 60
  );

  /**
   * Stored API Key state
   */
  protected $api_key;

  /**
   * Initialize a Resumator instance.
   *
   * API can be set by passing the $api_key param or
   * by setting the environment variable `RESUMATOR_API_KEY`
   *
   * @param string $api_key The API Key for your app
   */
  public function __construct($api_key = null) {
    if( false !== getenv("RESUMATOR_API_KEY") ) {
      $this->api_key = getenv("RESUMATOR_API_KEY");
    } else {
      $this->api_key = $api_key;
    }

    if(null == $this->api_key)
      throw new Exception('Resumator requires an API Key');
  }

  /**
   * Get the API Key
   *
   * @return string the API Key
   */
  public function getApiKey() {
    return $this->api_key;
  }

  /**
   * Get a Job by ID (job_xxx_xxx)
   *
   * @param string $job_id the Job ID
   * @return object the Job data object
   */
  public function getJob($job_id) {
    return $this->call(array("jobs", $job_id));
  }

  /**
   * Create a Job
   *
   * @param array $params parameters for the new Job
   * @return object the Job data object
   */
  public function createJob($params) {
    return $this->call("jobs", $params, "POST");
  }

  /**
   * Get Jobs
   *
   * @param array $params parameters for filtering results
   * @return object the Jobs data object
   */
  public function getJobs($params = array()) {
    return $this->call("jobs", $params);
  }

  /**
   * Call the API
   *
   * @param mixed $endpoint the API endpoint to request - can be a string or single dimension array
   * @param array $params query parameters for the request
   * @param string $http_method the HTTP request method (GET, POST)
   * @return object the request response data
   */
  private function call($endpoint, $params = array(), $http_method = "GET") {
    $ch = curl_init();
    $opts = self::$CURL_OPTS;

    $url = $this->buildURL(self::API_URL . self::API_VERSION, $endpoint);

    if( isset($params) ) {
      if( $http_method == "POST" ) {
        $opts[CURLOPT_POST] = count($params);
        $opts[CURLOPT_POSTFIELDS] = http_build_query($params, null, "&");
      } else {
        $url = $this->buildURL($url, $params);
      }
    }

    $opts[CURLOPT_URL] = $url . "?apikey=" . $this->getApiKey();

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if( $status !== 200 ) {
      return @json_decode($result);
    } else {
      return @json_decode($result);
    }

    curl_close($ch);
  }

  /**
   * Build a URL
   *
   * @param string $base the base url
   * @param mixed $endpoint a string, normal, or associative array of endpoints
   * @return string the formatted url
   */

  private function buildURL($base, $endpoint) {
    $return_url = $base;
    if( gettype($endpoint) == "string") {
      $return_url .= "/" . $endpoint;
    } else if( gettype($endpoint) == "array") {
      foreach($endpoint as $key => $value) {
        if( gettype($key) == "string") {
          $return_url .= "/" . $key . "/" . $value;
        } else {
          $return_url .= "/" . $value;
        }
      }
    }

    return $return_url;
  }

}