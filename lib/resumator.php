<?php
if (!function_exists('curl_init')) {
  throw new Exception('Resumator needs the CURL PHP extension.');
}
if (!function_exists('json_decode')) {
  throw new Exception('Resumator needs the JSON PHP extension.');
}

require_once "inflector.php";
use Doctrine\Common\Inflector\Inflector as DoctrineInflector;

class Resumator {
  /**
   * Class Version
   */
  const VERSION = "0.2.5";

  /**
   * API Base URL
   */
  const API_URL = "https://api.resumatorapi.com/";

  /**
   * API Version
   */
  const API_VERSION = "v1";

  /**
   * Endpoints
   *
   * More details on endpoints: http://resumatorapi.com/v1/
   *
   * @var array
   */
  public static $endpoints = array(
    "activities" => array(
      "GET"  => "/activity_id"
    ),
    "applicants" => array(
      "GET"  => "/applicant_id",
      "POST" => "/"
    ),
    "applicants2jobs" => array(
      "GET"  => "/appjob_id",
      "POST" => "/"
    ),
    "categories" => array(
      "GET"  => "/category_id",
      "POST" => "/"
    ),
    "categories2applicants" => array(
      "GET"  => "/pro2cat_id",
      "POST" => "/"
    ),
    "contacts" => array(
      "GET"  => "/contact_id"
    ),
    "files" => array(
      "GET"  => "/file_id",
      "POST" => "/"
    ),
    "jobs" => array(
      "GET"  => "/job_id",
      "POST" => "/"
    ),
    "notes" => array(
      "POST" => "/"
    ),
    "questionnaire_answers" => array(
      "POST" => "/"
    ),
    "questionnaire_questions" => array(),
    "tasks" => array(
      "GET"  => "/task_id"
    ),
    "users" => array(
      "GET"  => "/user_id"
    )
  );

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
   *
   * @var string
   */
  protected $api_key;

  /**
   * Cache configuration
   *
   * @var array ENABLED: use caching?, PATH: the absolute path to store cache files, EXPIRES: cache validity (in seconds)
   */
  public $cache = array(
    "ENABLED" => true,
    "PATH"    => "./cache/",
    "EXPIRES" => 86400 // 86400 = 24hrs
  );

  /**
   * Initialize a Resumator instance.
   *
   * API can be set by passing the $api_key param or
   * by setting the environment variable `RESUMATOR_API_KEY`
   *
   * @param string $api_key The API Key for your app
   */
  public function __construct($api_key = null) {
    if( "" != getenv("RESUMATOR_API_KEY") ) {
      $this->api_key = getenv("RESUMATOR_API_KEY");
    } else {
      $this->api_key = $api_key;
    }

    if(empty($this->api_key))
      throw new Exception('Resumator requires an API Key');

    // Create cache directory if not exists
    mkdir($this->cache['PATH'], 0755, true);
  }

  /**
   * Intercept methods and map to self::$endpoints
   *
   */
  public function __call($method_name, $arguments) {
    $inflector = new DoctrineInflector();
    preg_match("/(get|post)/", $method_name, $request_matches);

    $http_method = array_unique($request_matches);
    $http_method = $http_method[0];

    $method = str_replace($http_method, "", $method_name);
    $method = strtolower(preg_replace('/(?<!^)(?<!\d)([A-Z])/', '_$1', $method));
    $endpoint = $inflector::pluralize($method);

    if( !array_key_exists($endpoint, self::$endpoints) )
      throw new Exception("Endpoint '/{$method}' does not exist");

    # POST requests
    if($http_method == "post") {
      if( empty($arguments) )
        throw new Exception("POST requests to '/{$endpoint}' requires arguments");

      return $this->apiRequest($endpoint, $arguments[0], "POST");
    # GET requests
    } else {
      # method is different than endpoint (singular) and args exists
      # probably asking for a single resource
      if($method !== $endpoint && empty($arguments)) {
        $singular_endoint = self::$endpoints[$endpoint][strtoupper($http_method)];
        throw new Exception("Endpoint '/{$endpoint}{$singular_endoint}' requires an argument");
      }

      if(!empty($arguments)) {
        return $this->apiRequest($endpoint, $arguments[0]);
      } else {
        return $this->apiRequest($endpoint);
      }
    }

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
   * Call the API
   *
   * @param mixed $endpoint the API endpoint to request - can be a string or single dimension array
   * @param array $params query parameters for the request
   * @param string $http_method the HTTP request method (GET, POST)
   * @return object the request response data
   */
  private function apiRequest($endpoint, $params = array(), $http_method = "GET") {
    $url = $this->buildURL(self::API_URL . self::API_VERSION, $endpoint);

    /**
     * Only allow caching for GET requests
     */
    if($this->cache['ENABLED'] && $http_method == "GET") {
      // dynamic cache filename from sha1 hash
      $cacheFile = sha1($url . json_encode($params));
      $cache = $this->readCache($cacheFile);
      if ($cache) {
        return json_decode($cache);
      }
    }

    $ch = curl_init();
    $opts = self::$CURL_OPTS;

    if( isset($params) ) {
      if( $http_method == "POST" ) {
        $params['apikey'] = $this->getApiKey();
        $opts[CURLOPT_POST] = count($params);
        $opts[CURLOPT_POSTFIELDS] = json_encode($params);
        $opts[CURLOPT_HTTPHEADER] = array('Content-Type: application/json');
      } else {
        $url = $this->buildURL($url, $params);
      }
    }

    $opts[CURLOPT_URL] = $url . "?apikey=" . $this->getApiKey();

    curl_setopt_array($ch, $opts);
    $result = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    /** Write cache only for GET request */
    if($this->cache['ENABLED'] && $http_method == "GET") {
      $this->writeCache($cacheFile, $result);
    }

    return @json_decode($result);
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

  /**
   * Read the cache file, if it exists and within the expiration time
   *
   * @param bool|false $file the filename, without extension
   * @return bool|string the cached data, or false on failure
   */
  private function readCache($file = false) {
    $cache = $this->cache['PATH'] . $file . ".cache";
    if(file_exists($cache)) {
      if(filemtime($cache) > time() - $this->cache['EXPIRES']) {
        // Returned cached data
        $data = @file_get_contents($cache);
        return $data;
      } else {
        // Attempt to delete the cache file
        @unlink($cache);
      }
    }
    return false;
  }

  /**
   * Write to the specified cache file
   *
   * @param bool|false $file the filename, without extension
   * @param string $data the data to write
   * @return bool the result of the write
   */
  private function writeCache($file = false, $data = "") {
    $cache = $this->cache['PATH'] . $file . ".cache";
    $fp = fopen($cache, "w");
    if($fp) {
      $result = fwrite($fp, $data);
      fclose($fp);
      return $result;
    }
    return false;
  }
}