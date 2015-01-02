# sliver

A tiny PHP test framework

## Quick, quick start

* Rope in `sliver` as a Composer dependency:
```
    "require": {
        "maxvu/sliver": "~1.0"
    }
```
* Create test classes by extending `\Sliver\TestSuite`. In the constructor, use the method `$this->test( string $name, callable $test )` to define your tests:
```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    public function __construct () {
      $this->test( 'one plus one is two', function () {
        return 1 + 1;
      })->equals(2);
    }
  }
```
* Use chainable methods `equals( $value )`, `fuzzyEquals( $value )`, `doesNotEqual( $value )`, `excepts()`, `doesNotExcept()`, `exceptsWithCode( $code )`, `takesLessThan( $seconds )` to apply conditions for the test's success.
* Use Composer script `sliver` to run all tests in the current working directory, or provide it a directory to crawl as an argument.
```
[user@host myapp]# composer sliver

  SampleClass
    [ OK ] one plus one is two [0.00007s]
  1 of 1 tests passed in 0.00056s
```

## Fixtures and setup

Declare arbitrary methods and dependencies on your test class and they will become available from within the tests. Use `$this->beforeEach( closure $c )` and `$this->aftereEach( closure $c )` to set them up and tear them down (similar to JUnit's `@Before` and `@After` annotations).
```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    private db;
    private function setUpDatabase () {
        $this->db = new DatabaseClient();
        $this->db->connect();
    }
    private function tearDownDatabase () {
        $this->db->disconnect();
    }
    public function __construct () {
      $this->beforeEach( function () { $this->setUpDatabase(); } );
      $this->afterEach( function () { $this->tearDownDatabase(); } );
      $this->test( 'echo record', function () {
        $this->database->put("pi", "3.14");
        return $this->database->get("pi")
      })->equals("3.14");
    }
  }
```

