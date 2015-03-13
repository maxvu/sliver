<?php

  namespace Sliver\Tests\Spy;
  use Sliver\Spy\Spy;
  use Sliver\TestCondition\TestCondition;
  use Sliver\TestCondition\ExceptionTestCondition;
  
  class Bomb {
    public function __construct () { throw new \Exception( 'BOOM!' ); }
  }
  
  class Value {
    public $x;
    public function __construct ( $x ) { $this->x = $x; }
    public function inc () { $this->x++; return $this; }
    public function get () { return $this->x; }
  }
  
  class Secret {
    private $x;
    public function __construct ( $x ) { $this->x = $x; }
    private function inc () { $this->x++; return $this; }
  }

  class SpyTest extends \Sliver\TestSuite\TestSuite {
    
    public function canPeekAndManipulateMembers () {
      // Public
      $spy = $this->spy( 'Sliver\Tests\Spy\Value', array( 54 ) );
      $this->assert( $spy->get() )->eq( 54 );
      $spy->x = 9999;
      $this->assert( $spy->inc()->get() )->eq( 10000 );
      $this->assert( $spy->x )->eq( 10000 );
      // Private
      $spy = $this->spy( 'Sliver\Tests\Spy\Secret', array( 61 ) );
      $this->assert( $spy->x )->eq( 61 );
    }
    
    public function createWithoutArgsDoesntConstruct () {
      $spy = $this->spy( 'Sliver\Tests\Spy\Bomb' );
    }
    
    public function createWithArgsDoesConstruct () {
      $this->shouldThrowException( 'BOOM!' );
      $spy = $this->spy( 'Sliver\Tests\Spy\Bomb', array() );
    }
    
    public function canConstructAfterTheFact () {
      $this->shouldThrowException( 'BOOM!' );
      $spy = $this->spy( 'Sliver\Tests\Spy\Bomb' );
      $spy->summon()->construct();
    }
    
    public function canManipulateMethods () {
      // Can replace a function
      $spy = $this->spy( 'Sliver\Tests\Spy\Bomb' );
      $spy->summon()->stub( '__construct', function () { echo 'DEFUSED'; } );
      $this->shouldOutput( 'DEFUSED' );
      $spy->summon()->construct();
      
      // Can replace then call a private function
      $spy = $this->spy( 'Sliver\Tests\Spy\Secret', array( 200 ) );
      $spy->summon()->stub( 'inc', function () { $this->x *= 2; return $this; } );
      $spy->inc();
      $this->assert( $spy->x )->eq( 400 );
    }
    
    public function canSetUnsetIsset () {
      $spy = $this->spy('Sliver\Tests\Spy\Value');
      $this->assert( isset( $spy->otherValue ) )->false();
      $spy->otherValue = 77;
      $this->assert( isset( $spy->otherValue ) )->true();
      unset( $spy->otherValue );
      $this->assert( isset( $spy->otherValue ) )->false();
    }
    
  };
  
?>