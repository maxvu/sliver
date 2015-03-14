<?php

  namespace Sliver\Tests\TestSuite;
  use Sliver\Test\Test;
  use Sliver\TestSuite\TestSuite;
  use Sliver\TestSuite\TestSuiteContainer;

  class TestSuiteContainerTest extends \Sliver\TestSuite\TestSuite {
  
    public function emptySuite () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->run();
      $this->assert( $ts->passed() )->true();
      $this->assert( $ts->getTests() )->len( 0 );
      $this->assert( $ts->getPassingTests() )->len( 0 );
      $this->assert( $ts->getFailingTests() )->len( 0 );
      $this->assert( $ts->getName() )->eq( 'Sliver\TestSuite\TestSuite' );
      $this->assert( $ts->getTotalTime() )->eq( 0.0 );
    }
    
    public function passingSuite () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->addTest( 'A', function () {} )->addTest( 'B', function () {} );
      $ts->run();
      $this->assert( $ts->passed() )->true();
      $this->assert( $ts->getTests() )->len( 2 );
      $this->assert( $ts->getPassingTests() )->len( 2 );
      $this->assert( $ts->getFailingTests() )->len( 0 );
      $this->assert( $ts->getName() )->eq( 'Sliver\TestSuite\TestSuite' );
      $this->assert( $ts->getTotalTime() )->gt( 0.0 );
    }
    
    public function failingSuite () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->addTest( 'A', function () {} );
      $ts->addTest( 'B', function () { $this->assert( 1 + 1 )->eq( 3 ); } );
      $ts->run();
      $this->assert( $ts->passed() )->false();
      $this->assert( $ts->getTests() )->len( 2 );
      $this->assert( $ts->getPassingTests() )->len( 1 );
      $this->assert( $ts->getFailingTests() )->len( 1 );
      $this->assert( $ts->getName() )->eq( 'Sliver\TestSuite\TestSuite' );
      $this->assert( $ts->getTotalTime() )->gt( 0.0 );
    }
    
    public function implicitNoException () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->addTest( 'A', function () { throw new \Exception( '!!!' ); } );
      $ts->run();
      $this->assert( $ts->passed() )->false();
    }
    
    public function explicitException () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->addTest( 'A', function () {
        $this->shouldThrowException();
        throw new \Exception( '!!!' ); 
      });
      $ts->run();
      $this->assert( $ts->passed() )->true();
    }
    
    public function failExplicitExceptionWithoutAnException () {
      $ts = new TestSuiteContainer( $this->spy( '\Sliver\TestSuite\TestSuite' ) );
      $ts->addTest( 'A', function () {
        $this->shouldThrowException();
      });
      $ts->run();
      $this->assert( $ts->passed() )->false();
    }
  
  }