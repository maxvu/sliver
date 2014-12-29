# sliver

A tiny PHP test framework

## Quick, quick start

* Rope in `sliver` as a Composer dependency:
```
    "require": {
        "maxvu/sliver": "~1.0"
    }
```
* Create a new class that extends `\Sliver\TestSuite`. In its constructor, use the method `$this->test( string $name, callable $test )` to define your tests:
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
* Use Composer script `sliver` to run all tests in the current working directory, or provide it a directory to crawl.
```
# composer sliver

  Condition
    [ OK ] equals() true on equality [0.00012s]
    [ OK ] equals() false on inequality [0.00004s]
    [ OK ] equals() false on fuzzy equality [0.00004s]
    [ OK ] equals() false on exception [0.00009s]
    [ OK ] fuzzyEquals() true on equality [0.00004s]
    [ OK ] fuzzyEquals() false on inequality [0.00004s]
    ...
  21 of 21 tests passed in 1.00671s

```

