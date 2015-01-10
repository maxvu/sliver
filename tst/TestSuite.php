<?php

  namespace Sliver\Tests;
  use Sliver\Test as SliverTest;
  
  class ContainedTestSuite extends \Sliver\TestSuite {
    
    public function define () {}
    
    public function __get ( $key ) {
      if ( isset( $this->key ) )
        return $this->{$key};
      return NULL;
    }
    
    public function __beforeAll ( $closure ) { $this->__beforeAll = $closure; }
    public function __afterAll ( $closure ) { $this->__afterAll = $closure; }
    public function __beforeEach ( $closure ) { $this->__beforeEach = $closure; }
    public function __afterEach ( $closure ) { $this->__afterEach = $closure; }
    
  }
  
  class TestSuite extends \Sliver\TestSuite {
  
    public function define () {
    
      $this->test( 'TestSuite::assert()', function () {
        $ts = new ContainedTestSuite();
        $ts->test( NULL, function () { 
          $this->assert( 'false', FALSE );
        });
        $this->assert(
          'failed assert yields FALSE',
          $ts->run()->allPassed() === FALSE
        );
        
        $ts = new ContainedTestSuite();
        $ts->test( NULL, function () { 
          $this->assert( 'true', TRUE );
        });
        $this->assert(
          'successful assert yields TRUE',
          $ts->run()->allPassed() === TRUE
        );
      });
      
      $this->test( 'TestSuite::assertEquals()', function () {
        $ts = new ContainedTestSuite();
        $ts->test( NULL, function () { 
          $this->assertEquals( 1, 1 );
        });
        $this->assert(
          'assertEquals on equality yields TRUE',
          $ts->run()->allPassed() === TRUE
        );
        
        $ts = new ContainedTestSuite();
        $ts->test( NULL, function () { 
          $this->assertEquals( 1, 3 );
        });
        $this->assert(
          'assertEquals on inequality yields FALSE',
          $ts->run()->allPassed() === FALSE
        );
        
      });
      
      $this->test( 'TestSuite::beforeAll()', function () {
        $ts = new ContainedTestSuite();
        $ts->__beforeAll( function () { $this->x = 7; } );
        $ts->test( NULL, function () { 
          return $this->x;
        })->equals( 7 );
        return $ts->run()->allPassed();
      })->equals( TRUE );
      
      $this->test( 'TestSuite::afterAll()', function () {
        $ts = new ContainedTestSuite();
        $ts->__afterAll( function () { $this->x = 21; } );
        $ts->test( NULL, function () {});
        $ts->run();
        return $ts->x;
      })->equals( 21 );
      
      $this->test( 'TestSuite::beforeEach()', function () {
        $ts = new ContainedTestSuite();
        $ts->__beforeAll( function () { $this->x = 0; } );
        $ts->__beforeEach( function () { $this->x += 3; } );
        for ( $i = 0; $i < 10; $i++ )
          $ts->test( NULL, function () { $this->x++; });
        $ts->run();
        return $ts->x;
      })->equals( 40 );
      
      $this->test( 'TestSuite::afterEach()', function () {
        $ts = new ContainedTestSuite();
        $ts->__beforeAll( function () { $this->x = 0; } );
        $ts->__afterEach( function () { $this->x += 3; } );
        for ( $i = 0; $i < 8; $i++ )
          $ts->test( NULL, function () { $this->x += 2; });
        $ts->run();
        return $ts->x;
      })->equals( 40 );
      
    }
    
  };
  
