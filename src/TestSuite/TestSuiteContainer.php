<?php

  namespace Sliver\TestSuite;
  use Sliver\Spy\Spy;
  use Sliver\Test\Test;
  use Sliver\TestCondition\TestCondition;
  use ReflectionClass;

  class TestSuiteContainer {

    private $suite;
    private $tests;
    
    public static function build ( $suiteName ) {
      $suite = Spy::create( $suiteName );
      $container = new TestSuiteContainer( $suite );
      foreach ( static::getSuiteTests( $suiteName ) as $name => $test )
        $container->addTest( $name, $test );
      return $container;
    }

    public function __construct ( $suite ) {
      $this->suite = $suite;
      $this->tests = array();
    }
    
    public function addTest ( $name, $closure ) {
      $this->tests[$name] = new Test( $name, $closure->bindTo( $this->suite ) );
    }

    public function run () {
      $this->suite->summon()->construct();
      foreach ( $this->tests as $key => $test ) {
        $this->suite->__currentTest = $test;
        $test->run();
        if ( !$test->expectsException() )
          $test->addCondition( new TestCondition(
            'should not throw exception',
            function ( $result ) { return $result->getException() === NULL; }
          ));
        $this->tests[$key] = clone $this->suite->__currentTest;
      }
      return $this;
    }

    public function passed () {
      return sizeof( array_filter( $this->tests, function ( $test ) {
        return !$test->passed();
      })) === 0;
    }

    public function getTests () {
      return $this->tests;
    }

    public function getPassingTests () {
      return array_filter( $this->tests, function ( $t ) {
        return $t->passed();
      });
    }

    public function getFailingTests () {
      return array_filter( $this->tests, function ( $t ) {
        return !$t->passed();
      });
    }

    public function getName () {
      return $this->suite->summon()->getReflector()->getName();
    }

    public function getTotalTime () {
      $t = 0.0;
      foreach ( $this->tests as $test )
        $t += $test->getResult()->getDuration()->getTime();
      return $t;
    }
    
    public static function getSuiteTests ( $suiteName ) {
      $tests = array();
      $reflection = new ReflectionClass( $suiteName );
      $instance = $reflection->newInstanceWithoutConstructor();
      foreach ( $reflection->getMethods() as $method ) {
        if ( !$method->isPublic() ) continue;
        if ( $method->isConstructor() ) continue;
        if ( $method->isDestructor() ) continue;
        if ( $method->isAbstract() ) continue;
        if ( $method->isStatic() ) continue;
        $tests[$method->getName()] = $method->getClosure( $instance );
      }
      return $tests;
    }

  };

?>
