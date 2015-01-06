<?php

  namespace Sliver\Tests;
  use Sliver\Test as SliverTest;

  class Test extends \Sliver\TestSuite {
  
    public function define () {
    
      $this->test( 'equals() true when equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->equals(2)->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'equals() false when fuzzy equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->equals('2')->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'equals() false when not equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->equals(3)->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'fuzzyEquals() true when equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->fuzzyEquals(2)->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'fuzzyEquals() true when fuzzy-equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->fuzzyEquals('2')->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'fuzzyEquals() false when not equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->fuzzyEquals(3)->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'doesNotEqual() false when equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->doesNotEqual(2)->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'doesNotEqual() false when fuzzy-equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->doesNotEqual('2')->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'doesNotEqual() true when not equal', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->doesNotEqual(3)->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'implicit doesNotExcept()', function () {
        $fn = function () { throw new \Exception('EX'); };
        return (new SliverTest( NULL, $fn ))->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'excepts() true on exception', function () {
        $fn = function () { throw new \Exception('EX'); };
        return (new SliverTest( NULL, $fn ))
          ->excepts()->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'excepts() false on no exception', function () {
        $fn = function () { return 1 + 1; };
        return (new SliverTest( NULL, $fn ))
          ->excepts()->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'takesLessThan() true on short test', function () {
        $fn = function () { return 1; };
        return (new SliverTest( NULL, $fn ))
          ->takesLessThan(.5)->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'takesLessThan() false on long test', function () {
        $fn = function () { usleep(20000); };
        return (new SliverTest( NULL, $fn ))
          ->takesLessThan(0.02)->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'outputContains() true on contains', function () {
        $fn = function () { echo '--HELLO WORLD--'; };
        return (new SliverTest( NULL, $fn ))
          ->outputContains('HELLO WORLD')->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'outputContains() true on contains', function () {
        $fn = function () { echo '--DLROW OLLEH--'; };
        return (new SliverTest( NULL, $fn ))
          ->outputContains('HELLO WORLD')->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'outputStartsWith() true on starts with', function () {
        $fn = function () { echo 'HELLO WORLD'; };
        return (new SliverTest( NULL, $fn ))
          ->outputStartsWith('HELLO')->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'outputStartsWith() false on contains', function () {
        $fn = function () { echo '--HELLO WORLD'; };
        return (new SliverTest( NULL, $fn ))
          ->outputStartsWith('HELLO')->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'outputStartsWith() false on does not contain', function () {
        $fn = function () { echo 'привет всем'; };
        return (new SliverTest( NULL, $fn ))
          ->outputStartsWith('HELLO')->run()->passed();
      })->equals( FALSE );
      
      $this->test( 'outputDoesNotContain() true on does not contain', function () {
        $fn = function () { echo 'привет всем'; };
        return (new SliverTest( NULL, $fn ))
          ->outputDoesNotContain('HELLO')->run()->passed();
      })->equals( TRUE );
      
      $this->test( 'outputDoesNotContain() false on contains', function () {
        $fn = function () { echo 'привет всем'; };
        return (new SliverTest( NULL, $fn ))
          ->outputDoesNotContain('привет')->run()->passed();
      })->equals( FALSE );
    
    }
  
  };

?>