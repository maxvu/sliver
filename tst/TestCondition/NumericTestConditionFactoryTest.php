<?php

  namespace Sliver\Tests\TestCondition;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  use Sliver\TestCondition\ExceptionTestCondition;

  class NumericTestConditionFactoryTest extends \Sliver\TestSuite\TestSuite {
    
    private function applyAssert ( $value, $fn ) {
      $t = new Test( '', function () {} );
      call_user_func( $fn, $t->getConditionFactory( $value ) );
      return $t->run()->passed();
    }

    public function comparators () {
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->isGreaterThan( 11 )
            ->gt( 11 )
            ->gte( 21 )
            ->ge( 21 )
            ->ge( 21 )
            ->ge( 22 )
            ->isGreaterThanOrEqualTo( 21 )
            ->isGreaterThanOrEqualTo( 22 )
            ->lt( 23 )
            ->lte( 23 )
            ->lte( 22 )
            ->le( 22 )
            ->isLessThan( 23 )
            ->isBetween( 11, 22 )
            ->isBetween( 11, 33 )
            ->isBetween( 22, 44 );
        })
      )->true();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->isGreaterThan( 33 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->gt( 33 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->isGreaterThanOrEqualTo( 33 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->gte( 33 );
        })
      )->false();
      
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->isLessThan( 11 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->isLessThanOrEqualTo( 11 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->lt( 11 );
        })
      )->false();
      
      $this->assert(
        $this->applyAssert( 22, function ( $f ) {
          $f->le( 11 );
        })
      )->false();
      
    }
    
  }