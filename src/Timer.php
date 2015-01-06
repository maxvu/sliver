<?php

  namespace Sliver;

  class Timer {
  
    protected $begin;
    protected $end;
    
    public function start () {
      $this->begin = microtime( TRUE );
      return $this;
    }
    
    public function stop () {
      if ( isset( $this->end ) )
        return $this->getTime();
      $this->end = microtime( TRUE );
      return $this->getTime();
    }
    
    public function getTime () {
      return $this->end - $this->begin;
    }
    
    public function __toString () {
      if ( !isset( $this->end ) )
        $this->stop();
      return number_format ( $this->end - $this->begin, 5 );
    }
    
    public static function measure ( callable $fn ) {
      $t = (new Timer())->start();
      call_user_func( $fn );
      return $t->stop();
    }
  
  };

?>