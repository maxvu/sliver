<?php
  
  class TestTest extends \Sliver\TestSuite {
    
    public function __construct () {
      
      $this->test( 'tautology', function () {
        return TRUE;
      })->equals( TRUE );
      
      $this->test( 'value is set', function () {
        return (new \Sliver\Test( '', function () {
          return 4;
        }))->run()->getValue();
      })->equals( 4 );
      
      $this->test( 'exception is set', function () {
        $t = (new \Sliver\Test( '', function () {
          throw new Exception( '!', 9 );
        }));
        return $t->run()->getExceptionMessage() === '!' && 
          $t->getExceptionCode() === 9;
      })->equals( TRUE );
      
      $this->test( 'timer is set', function () {
        return (new \Sliver\Test( '', function () {
          usleep(100000);
        }))->run()->getTimer()->getTime() > 0.1;
      });
        
      $this->test( 'runs again after reset', function () {
        $this->_global = 22;
        (new \Sliver\Test( '', function () {
          $this->_global++;
        }))->run()->reset()->run()->reset()->run();
        return $this->_global === 25;
      })->equals( TRUE );
      
    }
    
  };

?>
