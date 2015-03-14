<?php

  namespace Sliver\Tests\Utility;
  use Sliver\Utility\Timer;

  class TimerTest extends \Sliver\TestSuite\TestSuite {
    
    public function stop () {
      $t = new Timer();
      usleep( 10000 );
      $t->stop();
      $this->assert( $t->getTime() )->ge( 0.01 );
    }
    
    public function measure () {
      $wait = function () { usleep( 10000 ); };
      $this->assert( Timer::measure( $wait )->getTime() )->ge( 0.01 );
    }
    
    public function toString () {
      $this->assert( (string) (new Timer())->stop() )->len( 7 );
    }
    
  }
  
?>