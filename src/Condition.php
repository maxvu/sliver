<?php

namespace maxvu\sliver;

class Condition {
    
    protected $name;
    protected $closure;
    
    public function __construct (
        callable $closure,
        $name
    ) {
        $this->name = $name;
        $this->closure = $closure;
    }
    
    public function name () { return $this->name; }
    public function closure () { return $this->closure; }

};

?>