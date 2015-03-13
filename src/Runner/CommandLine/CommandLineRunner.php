<?php

  namespace Sliver\Runner\CommandLine;
  use Sliver\TestSuite\TestSuiteContainer;
  use Sliver\Utility\String;
  use Sliver\Utility\Timer;
  use Sliver\Runner\Runner;
  
  class CommandLineRunner implements Runner {
  
    protected $containers;
    protected $verbosity;
    protected $output;
    
    public function __construct ( $verbosity, $IO ) {
      $this->verbosity = $verbosity;
      $this->containers = array();
      $this->IO = $IO;
    }
    
    public function addTestSuite ( $suite ) {
      $this->containers[] = TestSuiteContainer::build( $suite );
    }
    
    public function getTestSuites () {
      return $this->containers;
    }
  
    public function run () {
      $totalTimer = new Timer();
      
      $numConditions = 0;
      $numConditionsPassing = 0;
      $numTests = 0;
      $numTestsPassing = 0;
      $numSuites = sizeof( $this->containers );
      $numSuitesPassing = 0;
      
      foreach ( $this->containers as $container ) {
        $container->run();
        foreach ( $container->getTests() as $test ) {
          $numConditions += $test->numConditions();
          $numConditionsPassing += $test->numPassingConditions();            
          if ( $test->passed() )
            $numTestsPassing++;
          $numTests++;
        }
        if ( $container->passed() )
           $numSuitesPassing++;
        $this->reportSuite( $container );
      }
      $cLine = "$numConditionsPassing / $numConditions conditions, ";
      $cLine .= "$numTestsPassing / $numTests tests and ";
      $cLine .= "$numSuitesPassing / $numSuites suites passed";
      if ( $numConditionsPassing === $numConditions ) {
        $this->IO->writeOk( $cLine, 0 )->newline();
        return 0;
      } else {
        $this->IO->writeBad( $cLine, 0 )->newline();
        return 1;
      }
    }
    
    private function reportSuite ( $suite ) {
      $tests = $suite->getTests();
      $numTestsPassing = sizeof( $suite->getPassingTests() );
      $numTests = sizeof ( $tests );

      $time = number_format( $suite->getTotalTime(), 5 );
      $subline = "$numTestsPassing / $numTests tests passed in {$time}s";
      
      $this->IO->writeNeutral( $suite->getName() );
      foreach ( $suite->getTests() as $test ) {
       $test->run();
       if ( !$test->passed() )
         $this->reportFailedTest( $test );
       elseif ( $this->verbosity > 1 )
        $this->reportPassingTest( $test );
          
      }
      if ( $numTestsPassing === $numTests )
        $this->IO->writeOk( $subline, 0 );
      else
        $this->IO->writeBad( $subline, 0 );
      $this->IO->newline();
    }
    
    private function reportPassingTest ( $test ) {
      $time = $test->getResult()->getDuration();
      $numConditions = sizeof( $test->getConditions() );
      $numPassingConditions = sizeof( $test->getPassingConditions() );
      $cLine = "$numPassingConditions / $numConditions in {$time}s";
      $this->IO->writeNeutral( $test->getName() . ", $cLine", 2 );
      
      if ( $this->verbosity > 1 ) {
        foreach ( $test->getConditions() as $condition ) {
          $this->IO->writeOk( $condition->getName(), 4 );
        }
      }
    }
    
    private function reportFailedTest ( $test ) {
      $name = $test->getName();
      $conditions = $test->getConditions();
      $numConditions = sizeof( $conditions );
      $numPassingConditions = sizeof( $test->getPassingConditions() );
      $result = $test->getResult();
      $returnValue = String::serialize( $result->getValue() );
      $ex = String::serialize( $result->getException() );
      $output = String::serialize( $result->getOutput() );
      $timer = $result->getDuration();
      $cLine = "$numPassingConditions / $numConditions in {$timer}s";
      
      $this->IO->writeNeutral( "$name, $cLine", 2 );
      $this->IO->writeNeutral( "output:       $output", 4 );
      $this->IO->writeNeutral( "return value: $returnValue", 4 );
      $this->IO->writeNeutral( "exception:    $ex", 4 );
      $this->IO->writeNeutral( "duration:     {$timer}s", 4 );
      foreach ( $conditions as $cond ) {
        $condName = $cond->getName();
        if ( !$result->satisfies( $cond ) )
          $this->IO->writeBad( "condition:    $condName", 4 );
        else
          $this->IO->writeOk( "condition:    $condName", 4 );
      }
    }
  
  };

?>