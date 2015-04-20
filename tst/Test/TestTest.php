<?php

  namespace Sliver\Tests\Test;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  use Sliver\TestCondition\ExceptionTestCondition;

  class TestTest extends \Sliver\TestSuite\TestSuite {

    public function canGetNameAndClosure () {
      $t = new Test( 'TEST01', function () { return 22; });
      $this->assert( $t->getName() )->equals( 'TEST01' );
      $this->assert( call_user_func( $t->getClosure() ) )->equals( 22 );
    }
    
    public function canSetGetConditions () {
      $t = new Test( 'TEST02', function () { return 23; });
      $t->addCondition( new TestCondition( 'COND', function () {} ) );
      $this->assert( $t->getConditions() )->size( 1 );
      $this->assert( $t->getConditions()[0]->getName() )->equals( 'COND' );
      $this->shouldTakeLessThan(1);
    }
    
    public function runProducesResult () {
      $t = new Test( 'TEST03', function () { return 29; });
      $t->run();
      $this->assert( $t->getResult() )->notNull();
      $this->assert( $t->getResult()->getValue() )->eq( 29 );
    }
    
    public function resultHasAllFields () {
      $t = new Test( 'TEST04', function () { usleep(1000); return 29; });
      $t->run();
      $this->assert( $t->getResult()->getValue() )->equals( 29 );
      $this->assert( $t->getResult()->getException() )->null();
      $this->assert( $t->getResult()->getDuration() )->notNull();
      $this->assert( $t->getResult()->getDuration()->getTime() )->gt(1e-4);
    }
    
    public function conditionsAppliedCorrectly () {
      $nop = function () {};
      $pass = new TestCondition( 'PASS', function ( $result ) { return TRUE; } );
      $fail = new TestCondition( 'FAIL', function ( $result ) { return FALSE; } );
      $makeTest = function ( $conditions ) use ( $nop ) {
        $test = new Test( '', $nop );
        foreach ( $conditions as $c )
          $test->addCondition( $c );
        return $test->run();
      };
      
      $this->assert( $makeTest( array() )->passed() )->eq( TRUE );
      $this->assert( $makeTest( array( $pass ) )->passed() )->eq( TRUE );
      $this->assert( $makeTest( array( $fail ) )->passed() )->eq( FALSE );
      $this->assert( $makeTest( array( $pass, $pass ) )->passed() )->eq( TRUE );
      $this->assert( $makeTest( array( $pass, $fail ) )->passed() )->eq( FALSE );
      $this->assert( $makeTest( array( $fail, $fail ) )->passed() )->eq( FALSE );
    }
    
    public function getFailedConditions () {
      $nop = function () {};
      $pass = new TestCondition( 'PASS', function ( $result ) { return TRUE; } );
      $fail = new TestCondition( 'FAIL', function ( $result ) { return 'bad'; } );
      $makeTest = function ( $conditions ) use ( $nop ) {
        $test = new Test( '', $nop );
        foreach ( $conditions as $c )
          $test->addCondition( $c );
        return $test->run()->getFailedConditions();
      };
      
      $this->assert( $makeTest( array() ) )->size( 0 );
      $this->assert( $makeTest( array( $pass ) ) )->size( 0 );
      $this->assert( $makeTest( array( $fail ) ) )->size( 1 );
      $this->assert( $makeTest( array( $pass, $fail ) ) )->size( 1 );
      $this->assert( $makeTest( array( $fail, $fail ) ) )->size( 2 );
    }
    
    public function expectsException () {
      $doesntExpect = new TestCondition( 'ex', function ( $result ) {
        return $result->getException() === NULL;
      });
      $expects = new ExceptionTestCondition( 'noex', function ( $result ) {
          return $result->getException() != NULL;
      });
      $throws = function () { throw new \Exception(); };
      $doesntThrow = function () { return 0; };
      
      $tests = array(
        (new Test( '', $doesntThrow ))->addCondition($doesntExpect),
        (new Test( '', $throws ))->addCondition($doesntExpect),
        (new Test( '', $doesntThrow ))->addCondition($expects),
        (new Test( '', $throws ))->addCondition($expects),
      );
      
      $results = array(
        FALSE,
        FALSE,
        TRUE,
        TRUE
      );
      
      foreach ( $tests as $i => $test )
        $this->assert( $test->run()->expectsException() )->eq( $results[$i] );
      echo "MELROSE " , sizeof( $this->__currentTest->getConditions() ) , "\n";
    }
    
  };

?>