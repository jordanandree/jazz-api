# Resumator API PHP

[![Build Status](https://travis-ci.org/jordanandree/resumator-api.svg?branch=master)](https://travis-ci.org/jordanandree/resumator-api)

PHP API wrapper for the [Resumator API](http://www.resumatorapi.com/).

# Usage

Check out the [examples](examples) for a sample job listing implementation.

#### Minimal Example

```php
require "resumator-api/lib/resumator.php";

// setup with your API Key
$resumator = new Resumator("YOUR_API_KEY");

// return all Jobs
$jobs = $resumator->getJobs();

// return all Jobs without caching
$jobs = $resumator->getJobs();

// get a single Job
$job = $resumator->getJob($jobs[0]->id);

// create a job
$job_fields = array(
  "title"          => "New Job",
  "hiring_lead_id" => "YOUR_ID",
  "description"    => "Join us!",
  "job_status"     => 2 // draft status
);
$new_job = $resumator->postJob($job_fields);
```

#### Caching

The caching mechanism can be configured and toggled on or off prior to making any API calls. By design, caching is only available for GET API calls.

**Examples:**

```php
require "resumator-api/lib/resumator.php";

// setup with your API Key
$resumator = new Resumator("YOUR_API_KEY");

// set the default caching cache file expiration time (in seconds)
$resumator->cache['EXPIRES'] = 604800; // 1 week

// set the cache file save path
$resumator->cache['PATH'] = __DIR__ . DIRECTORY_SEPARATOR . "cache" . DIRECTORY_SEPARATOR;

// disable caching
$resumator->cache['ENABLED'] = false;

// enable caching
$resumator->cache['ENABLED'] = true;

```

### Composer

- Add the `jordanandree/resumator-api`: `@stable` into the require section of your composer.json.
- Run `composer install`.
- The example will look like this:

```php
if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$resumator = new Resumator("YOUR_API_KEY");
```

# Tests

Tests are a work-in-progress and coverage could be better.

You can run tests like so:
```bash
phpunit tests/tests.php
```

# Contributing

1. Fork it ( http://github.com/jordanandree/resumator-api-php/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request