<?php

  namespace Sliver\Tests\TestCondition;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  
  class TestConditionFactoryTest extends \Sliver\TestSuite\TestSuite {
    
    private function applyAssert ( $value, $fn ) {
      $t = new Test( '', function () {} );
      call_user_func( $fn, $t->getConditionFactory( $value ) );
      return $t->run()->passed();
    }
    
    public function equalTo () {
      
      // Simple TRUE
      $this->assert(
        $this->applyAssert( NULL, function ( $f ) {
          $f->equalTo( NULL );
        })
      )->eq( TRUE );
      
      // Simple FALSE
      $this->assert(
        $this->applyAssert( 65, function ( $f ) {
          $f->equalTo( 71 );
        })
      )->eq( FALSE );
      
      // Fuzzy equals -- false
      $this->assert(
        $this->applyAssert( "22", function ( $f ) {
          $f->equalTo( 22 );
        })
      )->eq( FALSE );
      
      // Alias: eq()
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->eq( 22 );
        })
      )->eq( TRUE );
      
    }
  
  }
  
?>