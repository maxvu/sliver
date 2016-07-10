<?php

namespace maxvu\sliver;

class ConsoleRunner extends Runner {
    
    const GREEN = "\033[1;32m";
    const RED   = "\033[1;31m";
    const RESET = "\033[0m";
    
    public $verbose;
    public $show_passing;
    public $color;
    
    public $longest_test_name_length;
    
    public function __construct ( $tests ) {
        $this->tests = $tests;
        $this->verbose = false;
        $this->show_passing = false;
        $this->color = true;
        $this->longest_test_name_length = 0;
    }
    
    protected function say ( $what ) {
        return $what;
    }
    
    protected function say_green ( $what ) {
        if ( $this->color )
            return $this::GREEN . $what . $this::RESET;
        else
            return $what;
    }
    
    protected function say_red ( $what ) {
        if ( $this->color )
            return $this::RED . $what . $this::RESET;
        else
            return $what;
    }
    
    protected function tab ( $column ) {
        return $column . str_repeat(
            ' ', $this->longest_test_name_length - strlen( $column )
        );
    }
    
    public function show_passing ( $show = null ) {
        if ( $show !== null ) {
            $this->show_passing = $show;
            return $this;
        }
        return $this->show_passing;
    }
    
    public function verbose ( $verbose = null ) {
        if ( $verbose !== null ) {
            $this->verbose = $verbose;
            return $this;
        }
        return $this->verbose;
    }
    
    public function color ( $color = null ) {
        if ( $color !== null ) {
            $this->color = $color;
            return $this;
        }
        return $this->color;
    }
    
    public function run () {
        
        # Just looks better separated by a newline on either end.
        print "\n";
        
        # Console spacing.
        foreach ( $this->tests as $test ) {
            if ( strlen( $test->name() ) > $this->longest_test_name_length )
                $this->longest_test_name_length = strlen( $test->name() );
        }
        
        foreach ( $this->tests as $test ) {
            $test->run();
            $this->print_test( $test );
        }
        
        $this->print_summary_stats();
    }
    
    public function print_test ( $test ) {
        if ( $test->passed() && $this->show_passing ) {
            printf(
                "    %s [ OK ] (%.05fs)\n",
                $this->say_green( $this->tab( $test->name() ) ),
                $test->result()->duration()
            );
            if ( $this->verbose )
                foreach ( $test->conditions() as $condition )
                    $this->print_condition( $test, $condition );
        } else if ( !$test->passed() ) {
            printf(
                "    %s [ !! ] (%.05fs)\n",
                $this->say_red( $this->tab( $test->name() ) ),
                $test->result()->duration()
            );
            foreach ( $test->conditions() as $condition ) {
                $this->print_condition( $test, $condition );
            }
        }
    }
    
    public function print_condition ( $test, $condition ) {
        if ( $test->result()->satisfies( $condition ) )
            printf( "        O %s \n", $this->say_green( $condition->name() ) );
        else 
            printf( "        X %s \n", $this->say_red( $condition->name() ) );
    }
    
    public function print_summary_stats () {
        $num_tests = 0;
        $num_tests_passing = 0;
        $num_conds = 0;
        $num_conds_passing = 0;
        
        foreach ( $this->tests as $test ) {
            $num_tests++;
            $num_conds += sizeof( $test->conditions() );
            if ( $test->passed() ) $num_tests_passing++;
            foreach ( $test->conditions() as $condition ) {
                if ( $test->result()->satisfies( $condition ) )
                    $num_conds_passing++;
            }
        }
        
        printf(
            "\n    %d / %d tests passing (%.02f%%)\n",
            $num_tests_passing,
            $num_tests,
            ( $num_tests_passing / $num_tests ) * 100
        );
        
        printf(
            "    %d / %d conditions passing (%.02f%%)\n\n",
            $num_conds_passing,
            $num_conds,
            ( $num_conds_passing / $num_conds ) * 100
        );
        
    }

};

?>