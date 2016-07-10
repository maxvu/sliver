# sliver

A simple, closure-based Test class.

## Quick start

Rope in `sliver` as a Composer dependency:
```
# composer require --dev maxvu/sliver
```

Instantiate `maxvu\sliver\Test`, using a closure exhibiting the behavior to test as its only constructor argument.

```php
<?php
use maxvu\sliver\Test;

$tests = [
    (new Test( function ( $test ) {
        # ...
    } ))->name( '1 + 1 = 2' );
];
?>
```

Use `$test->assert()` to capture a value and any of the following methods to apply expectations to it:
```php
<?php
$test->assert( $a )->like( $b )       # $a == $b
$test->assert( $a )->eq( $b )         # $a === $b
$test->assert( $a )->ne( $b )         # $a != $b
$test->assert( $a )->gt( $b )         # $a > $b
$test->assert( $a )->ge( $b )         # $a >= $b
$test->assert( $a )->lt( $b )         # $a < $b
$test->assert( $a )->le( $b )         # $a <= $b
$test->assert( $a )->null( $b )       # $a === null
$test->assert( $a )->true( $b )       # $a === true
$test->assert( $a )->false( $b )      # $a === false
$test->assert( $a )->truthy( $b )     # $a
$test->assert( $a )->falsy( $b )      # !$a
$test->assert( $a )->contains( $b )   # in_array( $b, $a ) OR
                                      # false !== strpos( strval( $a ), strval( $b ) )
$test->assert( $a )->matches( $b )    # preg_match( $b, $a );
?>
```

Provide a list of these `Test`s to a `maxvu\sliver\Runner` (like `maxvu\sliver\ConsoleRunner`) and call `run()` on it:
```php
<?php
require 'vendor/autoload.php';
use maxvu\sliver\Test;
use maxvu\sliver\ConsoleRunner;

(new ConsoleRunner([
    (new Test( function ( $test ) {
        $test->assert( 1 + 1 )->eq( 2 );
    } ))->name( '1 + 1 = 2' )
]))->show_passing( 1 )->run();
?>
```

Have printed out to the console information about each test:

```
$ php tests.php

    1 + 1 = 2 [ OK ] (0.00045s)

    1 / 1 tests passing (100.00%)
    2 / 2 conditions passing (100.00%)

$
```
