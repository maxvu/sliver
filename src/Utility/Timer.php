<?php

  namespace Sliver\Utility;
  
  class Timer {
  
    private $start;
    private $stop;
  
    public function __construct ( $t = NULL ) {
      $this->start = microtime( TRUE );
    }
    
    public function stop () {
      if ( !isset( $this->stop ) ) $this->stop = microtime( TRUE );
      return $this;
    }
    
    public function getTime () {
      $this->stop();
      return $this->stop - $this->start;
    }
    
    public static function measure ( callable $fn ) {
      $t = new Timer();
      call_user_func( $fn );
      return $t->stop();
    }
    
    public function __toString () {
      return number_format ( $this->stop - $this->start, 5 );
    }
  
  };

?>