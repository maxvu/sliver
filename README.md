# sliver

`sliver` is a tiny, no-frills PHP test framework. It encapsulates tests as `Closure`s rather than test class methods and can capture information like return values, exceptions, running time, and `STDOUT` output. It ships as a Composer vendor binary and is intended to be run from the command line.

## Installation

* Rope in `sliver` as a Composer dependency:
```
    "require": {
        "maxvu/sliver": "~1.0"
    }
```

## Creating Tests

Organize your `sliver` tests in a common directory as class definitions extending  `\Sliver\TestSuite`. There are no assumptions about the names of the files or of the classes. Define in each of the test classes a public method `define()` and, for each test, place in this method a `$this->test( $name, $closure )` statement.

```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    public function define () {
      $this->test( 'a simple test', function () {
        return 1 + 1;
      });
    }
  }
?>
```

`sliver` determines tests' success by assessing a number of conditions applied to it. By default, the only condition implicit is that no exception is thrown. You may override this behavior or apply other conditions by chaining their methods at the definition:

```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    public function define () {
      $this->test( '1 + 1 = 2', function () {
        return 1 + 1;
      })->equals( 2 );
    }
  }
?>
```

The available conditions are:
* equals( $value )
* fuzzyEquals( $value )
* doesNotEqual( $value )
* excepts()
* takesLessThan( $seconds )
* outputContains( $str )
* outputStartsWith( $str )
* outputDoesNotContain( $str )

## Fixtures and Setup

The tests are run in the scope of the defining class, so any members you declare will become available within the closures without `use` clauses. To set dependencies, though, do not override the constructor and instead define a `beforeAll()` method. Deconstruct using the matching `afterAll()` or, wrapping each test, `beforeEach()` and `afterEach()`.

```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    private $db;
    public function beforeAll () {
      $this->db = (new Database())->connect();
    }
    public function afterAll () {
      $this->db->disconnect();
    }    
    public function define () {
      $this->test( 'get record', function () {
        return $db->get('key');
      })->equals( 'value' );
    }
  }
?>
```
## Assertions

For behavior that you'd like to bound within a test, you may use assertions in the forms `$this->assert( $description, $value )` and `$this->assertEquals( $v1, $v2 )`.

```php
<?php
  class SampleClass extends \Sliver\TestSuite {
    public function define () {
      $this->test( '1 + 1 = 2', function () {
        $this->assertEquals( 1 + 1, 2 );
      });
    }
  }
?>
```

## Running Tests

To run `sliver`, use the script in your Composer project's vendor binary directory:
```
[user@host myapp]# ./vendor/bin/sliver.php

  [ OK ] Sliver\Tests\ContainedTestSuite, 0 / 0 passed in [0.00003s]
  [ OK ] Sliver\Tests\TestSuite, 6 / 6 passed in [0.00182s]
  [ OK ] Sliver\Tests\Test, 24 / 24 passed in [0.02350s]
  [ OK ] Sliver\Tests\Value, 2 / 2 passed in [0.00042s]

  32 / 32 tests ( 74 / 74 conditions ) passed in [0.02770s]
```

Test failures will be reported with all captured information about the closure, along with all conditions it failed to satisfy:

```
  [ OK ] Sliver\Tests\ContainedTestSuite, 0 / 0 passed in [0.00003s]
  [ OK ] Sliver\Tests\TestSuite, 6 / 6 passed in [0.00184s]
  [ !! ] Sliver\Tests\Test, 23 / 24 passed [0.02256s]
    implicit doesNotExcept():
      value:     FALSE
      exception: NULL
      time:      0.00008s
      output:    (none)
      failed:    returns exactly TRUE
  [ OK ] Sliver\Tests\Value, 2 / 2 passed in [0.00040s]

  31 / 32 tests ( 73 / 74 conditions ) passed in [0.02841s]
```

The script will recursively scan the current directory and instantiate and use each subclass of `\Sliver\TestSuite`. If you have unencapsulated code in your project or experience poor performance due to the volume of files `include()`ed, provide the directory your tests are located in as the first argument and it will be crawled instead.

