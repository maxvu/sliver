<?php

  namespace Sliver\Tests\Utility;
  use Sliver\Utility\String;

  class StringTest extends \Sliver\TestSuite\TestSuite {
    
    public function serialize () {
    
      $cases = array(
        'NULL' => NULL,
        'TRUE' => TRUE,
        'FALSE' => FALSE,
        'int 7' => 7,
        'int -1' => -1,
        'float 0.1' => 0.1,
        'exception "msg" (code 0)' => new \Exception( 'msg', 0 ),
        'object stdClass {  }' => new \stdClass(),
        '{  }' => array(),
        '{ int 0 => int 1 }' => array( 1 ),
        '{ int 0 => "HELLO" }' => array( 'HELLO' )
        
      );
      
      foreach ( $cases as $output => $input )
        $this->assert( String::serialize( $input ) )->eq( $output );
     
    }

    
  }
  
?>