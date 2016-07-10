<?php

namespace maxvu\sliver;

class Timer {

    protected $start;
    protected $stop;
    
    public function __construct () {
        $this->start = microtime( TRUE );
        $this->stop = null;
    }
    
    public function stop () {
        if ( $this->stop === null )
            $this->stop = microtime( TRUE );
        return $this->stop - $this->start;
    }
    
    public function get () {
        return $this->stop();
    }

};

?>