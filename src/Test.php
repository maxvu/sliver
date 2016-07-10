<?php

namespace maxvu\sliver;

class Test {

    protected $name;
    protected $closure;
    protected $conditions;
    protected $result;
    protected $should_except;
    
    public function __construct (
        callable $closure,
        $name = null
    ) {
        $this->name = $name || '';
        $this->closure = $closure;
        $this->conditions = [];
        $this->result = null;
        $this->should_except = false;
    }
    
    public function name ( $new_name = null ) {
        if ( is_string( $new_name ) ) {
            $this->name = $new_name;
            return $this;
        }
        return $this->name;
    }
    
    public function conditions () {
        return $this->conditions;
    }
    
    public function add_condition ( callable $closure, $name ) {
        $this->conditions[] = new Condition( $closure, $name );
    }
    
    public function result () {
        if ( $this->result === null )
            $this->run();
        return $this->result;
    }
    
    public function excepts () {
        $this->should_except = true;
        return $this;
    }
    
    public function run () {
        
        # Only run once.
        if ( $this->result !== null )
            return $this;
        
        # It's nice to have this as an implicit condition.
        if ( $this->should_except ) {
            $this->conditions[] = new Condition(
                function ( $result ) {
                    return $result->exception() !== null;
                },
                'should throw exception'
            );
        } else {
            $this->conditions[] = new Condition(
                function ( $result ) {
                    return $result->exception() === null;
                },
                'should not throw exception'
            );
        }
        
        ob_start();
        $value = null;
        $exception = null;
        $timer = new Timer();
        try {
            $value = call_user_func( $this->closure, $this );
        } catch ( \Exception $e ) {
            $exception = $e;
        }
        $output = ob_get_clean();
        $this->result = new Result(
            $value,
            $output,
            $timer->stop(),
            $exception
        );
        return $this;
        
    }
    
    public function passed () {
        if ( $this->result === null )
            $this->run();
        foreach ( $this->conditions as $condition ) {
            if ( !$this->result->satisfies( $condition ) ) {
                return false;
            }
        }
        return true;
    }
    
    public function fail () {
        $this->conditions[] = new Condition(
            function () { return 0; },
            'fail (deliberately)'
        );
        return $this;
    }
    
    public function assert ( $value ) {
        return new Value( $this, $value );
    }
  
};

?>