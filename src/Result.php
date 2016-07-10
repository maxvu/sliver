<?php

namespace maxvu\sliver;

class Result {
    
    protected $value;
    protected $output;
    protected $duration;
    protected $exception;
    
    public function __construct (
        $value,
        $output,
        $duration,
        $exception
    ) {
        $this->value = $value;
        $this->output = $output;
        $this->duration = $duration;
        $this->exception = $exception;
    }
    
    public function value () { return $this->value; }
    public function output () { return $this->output; }
    public function duration () { return $this->duration; }
    public function exception () { return $this->exception; }
    
    public function satisfies ( Condition $condition ) {
        return call_user_func( $condition->closure(), $this );
    }

};

?>