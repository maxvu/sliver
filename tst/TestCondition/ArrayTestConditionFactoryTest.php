<?php

  namespace Sliver\Tests\TestCondition;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  
  class ArrayTestConditionFactoryTest extends \Sliver\TestSuite\TestSuite {
    
    private function applyAssert ( $value, $fn ) {
      $t = new Test( '', function () {} );
      call_user_func( $fn, $t->getConditionFactory( $value ) );
      return $t->run()->passed();
    }
    
    public function contains () {
      
      $evens = array( 22, 44, 66, 88 );
      $capitals = array(
        'Sweden' => 'Stockholm',
        'Mongolia' => 'Ulan Bator',
        'Islas Malvinas' => 'Stanley'
      );
      
      // contains() TRUE
      $this->assert(
        $this->applyAssert( $evens, function ( $f ) {
          $f->contains( 44 );
        })
      )->eq( TRUE );
      
      // contains() FALSE
      $this->assert(
        $this->applyAssert( $evens, function ( $f ) {
          $f->contains( 11 );
        })
      )->eq( FALSE );
      
      // contains() associative TRUE
      $this->assert(
        $this->applyAssert( $capitals, function ( $f ) {
          $f->contains( 'Stockholm' );
        })
      )->eq( TRUE );
      
      // contains() associative FALSE
      $this->assert(
        $this->applyAssert( $capitals, function ( $f ) {
          $f->contains( 'Buenos Aires' );
        })
      )->eq( FALSE );
      
      // Alias: hasValue()
      $this->assert(
        $this->applyAssert( $evens, function ( $f ) {
          $f->hasValue( 66 );
        })
      )->eq( TRUE );
      
      // Alias: has()
      $this->assert(
        $this->applyAssert( $evens, function ( $f ) {
          $f->has( 66 );
        })
      )->eq( TRUE );
      
    }
    
    public function doesNotContain () {
      
      $odds = array( 33, 55, 77, 99 );
      
      $this->assert(
        $this->applyAssert( $odds, function ( $f ) {
          $f->doesNotContain( 26 );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( $odds, function ( $f ) {
          $f->doesNotContain( 99 );
        })
      )->eq( FALSE );
      
    }
    
    public function hasKey () {
      
      $worldLeaders = array(
        'Finland' => 'Sauli Niinisto',
        'Moldova' => 'Nicolae Timofti',
        'Turkey' => 'Recep Tayyip Erdogan'
      );
      
      $this->assert(
        $this->applyAssert( $worldLeaders, function ( $f ) {
          $f->hasKey( 'Turkey' );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( $worldLeaders, function ( $f ) {
          $f->hasKey( 'South Africa' );
        })
      )->eq( FALSE );
      
    }
    
    public function sizeIsSizeIsEmpty () {
      
      $empty = array();
      $sizeOne = array( 1 );
      $sizeTwo = array( 1, 2 );
      
      $this->assert(
        $this->applyAssert( $empty, function ( $f ) {
          $f->isEmpty();
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( $sizeTwo, function ( $f ) {
          $f->isEmpty();
        })
      )->eq( FALSE );
      
      $this->assert(
        $this->applyAssert( $sizeTwo, function ( $f ) {
          $f->size( 2 );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( $sizeOne, function ( $f ) {
          $f->size( 2 );
        })
      )->eq( FALSE );
      
      $this->assert(
        $this->applyAssert( $sizeTwo, function ( $f ) {
          $f->isSize( 2 );
        })
      )->eq( TRUE );
      
      $this->assert(
        $this->applyAssert( $sizeOne, function ( $f ) {
          $f->isSize( 2 );
        })
      )->eq( FALSE );
      
    }
  
  }
  
?>