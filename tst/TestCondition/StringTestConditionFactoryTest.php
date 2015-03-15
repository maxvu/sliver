<?php

  namespace Sliver\Tests\TestCondition;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  
  class StringTestConditionFactoryTest extends \Sliver\TestSuite\TestSuite {
    
    private function applyAssert ( $value, $fn ) {
      $t = new Test( '', function () {} );
      call_user_func( $fn, $t->getConditionFactory( $value ) );
      return $t->run()->passed();
    }
    
    public function hasSubstringContains () {
      // Simple TRUE
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->hasSubstring( 'DANCE' );
        })
      )->eq( TRUE );
      
      // Simple FALSE
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->hasSubstring( 'EMERGENCY' );
        })
      )->eq( FALSE );
      
      // strpos() returns 0, which could be FALSE...
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->hasSubstring( 'BREAKDANCE' );
        })
      )->eq( TRUE );
      
      // Alias: contains()
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->contains( 'BREAKDANCE' );
        })
      )->eq( TRUE );  
    }
    
    public function isLengthLenSizeEmpty () {
      
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->isLength( 0 );
        })
      )->eq( FALSE );
      
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->isLength( 10 );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->size( 10 );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->isEmpty();
        })
      )->eq( FALSE );
      
      $this->assert(
        $this->applyAssert( 'BREAKDANCE', function ( $f ) {
          $f->len( 10 );
        })
      )->eq( TRUE );
      
    }
    
    public function matches () {
      
      $this->assert(
        $this->applyAssert( '000-0001', function ( $f ) {
          $f->matches( '/^\d{3}-\d{4}$/' );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( '!!!', function ( $f ) {
          $f->matches( '/^\d{3}-\d{4}$/' );
        })
      )->eq( FALSE );
      
    }
  
  }
  
?>