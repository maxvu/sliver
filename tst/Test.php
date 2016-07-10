<?php

require __DIR__ . '/../vendor/autoload.php';
use maxvu\sliver\Test;
use maxvu\sliver\ConsoleRunner;

(new ConsoleRunner([

    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {} ))->run()->passed()
        )->eq( true );
    } ))->name( 'control' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                throw new \Exception();
            } ))->excepts()->run()->passed()
        )->eq( true );
    } ))->name( 'implicit no-exception, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                throw new \Exception();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'implicit no-exception, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->eq( 2 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'eq, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->eq( 3 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'eq, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->gt( 0 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'gt, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->gt( 2 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'gt, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->ge( 2 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'ge, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->ge( 3 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'ge, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->le( 2 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'le, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->le( 0 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'le, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->lt( 3 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'lt, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->lt( 0 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'lt, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( null )->null();
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'null, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 + 1 )->null();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'null, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( true )->true();
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'true, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 0 )->true();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'true, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( false )->false();
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'false, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 0 )->false();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'false, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 )->truthy();
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'truthy, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 0 )->truthy();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'truthy, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 0 )->falsy();
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'falsy, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 1 )->falsy();
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'falsy, negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert([
                    'one' => 1,
                    'two' => 2,
                ])->contains( 2 );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'contains (array), positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert([
                    'one' => 1,
                    'two' => 2,
                ])->contains( 3 );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'contains (array), negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 'mole sucks' )->contains( 'sucks' );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'contains (string), positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 'mole sucks' )->contains( '!!!' );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'contains (string), negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( null )->contains( '!!!' );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'contains (other), negative' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 'aaa' )->matches( '/[ab]+/' );
            } ))->run()->passed()
        )->eq( true );
    } ))->name( 'matches, positive' ),
    
    (new Test( function ( $test ) {
        $test->assert(
            (new Test( function ( $t2 ) {
                $t2->assert( 'cd' )->matches( '/[ab]+/' );
            } ))->run()->passed()
        )->eq( false );
    } ))->name( 'matches, negative' ),

    // 
    // with exception
    // eq positive
    // eq negative
    
]))->show_passing( 1 )->run();
    
    

?>