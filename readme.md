# Jazz (Resumator) API PHP

[![Build Status](https://travis-ci.org/jordanandree/jazz-api.svg?branch=master)](https://travis-ci.org/jordanandree/jazz-api)

PHP API wrapper for the [Jazz API](http://www.resumatorapi.com/).

# Usage

Check out the [examples](examples) for a sample job listing implementation.

#### Minimal Example

```php
require "jazz-api/lib/jazz.php";

// setup with your API Key
$jazz = new Jazz("YOUR_API_KEY");

// return all Jobs
$jobs = $jazz->getJobs();

// get a single Job
$job = $jazz->getJob($jobs[0]->id);

// create a job
$job_fields = array(
  "title"          => "New Job",
  "hiring_lead_id" => "YOUR_ID",
  "description"    => "Join us!",
  "job_status"     => 2 // draft status
);
$new_job = $jazz->postJob($job_fields);
```

### Composer

- Add the `jordanandree/jazz-api`: `@stable` into the require section of your composer.json.
- Run `composer install`.
- The example will look like this:

```php
if (($loader = require_once __DIR__ . '/vendor/autoload.php') == null)  {
  die('Vendor directory not found, Please run composer install.');
}

$jazz = new Jazz("YOUR_API_KEY");
```

# Tests

Tests are a work-in-progress and coverage could be better.

You can run tests like so:
```bash
phpunit tests/tests.php
```

# Contributing

1. Fork it ( http://github.com/jordanandree/jazz-api-php/fork )
2. Create your feature branch (`git checkout -b my-new-feature`)
3. Commit your changes (`git commit -am 'Add some feature'`)
4. Push to the branch (`git push origin my-new-feature`)
5. Create new Pull Request
