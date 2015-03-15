# sliver

A tiny PHP test framework.

## Quick start

* Rope in `sliver` as a Composer dependency:
```
# composer require --dev maxvu/sliver
```
* Create test classes by extending `\Sliver\TestSuite\TestSuite`. Use public, non-static functions for each test. Use the constructor and destructor for setup and tear-down. Use private methods and members to help set up and manage the tests.
```php
<?php
use \MyApp\SampleClass;
  class SampleClass extends \Sliver\TestSuite\TestSuite {
    /* ... */
    public function __construct () { /* ... setup */ }
    public function __destruct () { /* ... tear down */ }
    private function someUtility () { /* ... perform some repetitive thing */ }
    public function test1 () { /* ... */ }
    public function test2 () { /* ... */ }
    public function test3 () { /* ... */ }
  }
```
* Make your classes PSR-4 available under `autoload-dev` and add sliver as a `script`:
```json
    "autoload-dev" : {
        "psr-4": {
            "MyApp\\Tests" : "tst/"
        }
    },
    "scripts" : {
      "sliver" : ["Sliver\\Sliver::run"]
    }
```
* Use `$this->assert( ... )->{...}` to apply conditions for the test's success and `$this->spy( $className )` to simulate behavior.
* Use the Composer script to run and get output to the console:
```
[user@host myapp]# composer sliver

   [    ] Starting sliver
      [    ] Scanning autoload directories
      [    ] Found 1 test suites in 0.00598s

   [    ] MyApp\Tests\BoxTest
   [ OK ] 2 / 2 tests passed in 0.00143s

   [ OK ] 8 / 8 conditions, 2 / 2 tests and 1 / 1 suites passed
```
  * If a test fails, get detailed output about the result of the test:
 ```
      [user@host myapp]# composer sliver

      [    ] Starting sliver
      [    ] Scanning autoload directories
      [    ] Found 1 test suites in 0.00824s

      [    ] MyApp\Tests\BoxTest
         [    ] shakeDoesntDropThings, 2 / 3 in 0.00047s
               [    ] output:       ""
               [    ] return value: NULL
               [    ] exception:    exception "Box has too many things" (code 0)
               [    ] duration:     0.00047s
               [ OK ] condition:    int 7 === int 7
               [ OK ] condition:    TRUE === TRUE
               [ !! ] condition:    should not throw exception
        [ !! ] 1 / 2 tests passed in 0.00153s

        [ !! ] 7 / 8 conditions, 1 / 2 tests and 0 / 1 suites passed
```

