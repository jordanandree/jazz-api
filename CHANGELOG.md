# 0.4.0
- add file system caching support (thanks [robertark](https://github.com/robertark))

# 0.3.0

- rename package to jazz-api to follow resumator company name change
- fix POST requests that now use application/json content-type

# 0.2.5

* fix issue with passing arguments to a GET method call

# 0.2.4

- fixing __call mapping for questionnaire_answers and questionnaire_questions
- added tests for /tasks and /tasks/task_id endpoints
- more test coverage for API endpoints

# 0.2.3

- fixing PHP 5.3 syntax error for how __call() method find $http_method
- fixing test bootstrap
- updated examples
- rename to 'resumator-api'

# 0.2.2

- fixing function return in write context for __construct when checking env var

# 0.2.1

- copy Doctrine\Inflector to standalone require

# 0.2.0

- use __call magic method for mapping method calls to $endoints array
- added Doctrine\Inflector dependency to composer.json
- fix curl POSTFIELDS not being properly set

# 0.1.0

- initial project commit - working implementation for operations on Jobs