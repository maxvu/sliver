<?php

  namespace Sliver\Tests;
  use Sliver\Test as SliverTest;

  class Value extends \Sliver\TestSuite {
  
    public function define () {
    
      $this->test( 'passing value to value gives identity', function () {
        $v = \Sliver\Value::of( 25 );
        $v2 = \Sliver\Value::of( $v );
        $this->assertEquals( $v->get(), $v2->get() );
      });
      
      $this->test( 'serialization is sane', function () {
        $mapping = array(
          NULL,
          TRUE,
          FALSE,
          array( 1, FALSE, "HELLO" ),
          "HELLO",
          2.5,
          new \Exception( "EXCEPT", 21 ),
          function () {}
        );
        
        $expected = array(
          'NULL',
          'TRUE',
          'FALSE',
          '{int 0 => int 1 int 1 => FALSE int 2 => "HELLO" }',
          '"HELLO"',
          'float 2.5',
          'exception "EXCEPT" (code 21)',
          'object Closure {}'
        );
        
        foreach ( $mapping as $k => $v ) {
          $v = \Sliver\Value::of( $v );
          $this->assertEquals( (string) $v, $expected[$k] );
        }
        
      });
    }
    
  };

?>