#!/usr/bin/php
<?php

  /*
    Allow Sliver to run as its own binary and as a vendor binary.
  */

  $asSelf = __DIR__ . '/../vendor/autoload.php';
  $asDependency = __DIR__ . '/../../../../vendor/autoload.php';
  
  if ( is_readable( $asSelf ) ) {
    require( $asSelf );
  } else if ( is_readable( $asDependency ) ) {
    require( $asDependency );
  } else {
    echo "Couldn't find autoload.php\n";
    exit(1);
  }
  
  /*
    Recursively crawl and include() .php files (probably dangerous).
    Take directory as the first argument to limit.
  */
  
  $target_dir = getcwd();
  if ( isset( $argv[1] ) && file_exists( realpath( $argv[1] ) ) )
    $target_dir = realpath( $argv[1] );

  $it = new RegexIterator(
    new RecursiveIteratorIterator(
      new RecursiveDirectoryIterator( $target_dir )
    ),
    '/^.+\.php$/i',
    RecursiveRegexIterator::GET_MATCH
  );

  foreach ($it as $file) {
    if ( is_readable( $file[0] ) ) {
      ob_start();
      require_once $file[0];
      ob_get_clean();
    }
  }
  
  /*
    Collect and run().
  */
  
  $suites = array();
  $timer = new \Sliver\Timer();
  
  $labelOk = "[ \e[0;32mOK\033[0m ]";
  $labelBad = "[ \e[1;31m!!\033[0m ]";
  
  function displaySuite ( $suite, $result ) {
    $suiteName = get_class( $suite );
    $nTests = $result->numTests();
    $nTestsPassed = $result->numTestsPassed();
    $nConditions = $result->numConditions();
    $nConditionsPassed = $result->numConditionsPassed();
    $time = $result->getTimer();
    $labelOk = "[ \e[0;32mOK\033[0m ]";
    $labelBad = "[ \e[1;31m!!\033[0m ]";
    
    if ( $nConditions === $nConditionsPassed ) {
      echo "  $labelOk $suiteName, $nTestsPassed / $nTests passed in [{$time}s]\n";
    } else {
      echo "  $labelBad $suiteName, $nTestsPassed / $nTests passed [{$time}s]\n";
      foreach ( $suite->getTests() as $test ) {
        if ( !$test->passed() )
          displayFailedTest( $test );
      }
    }
  }
  
  function displayFailedTest ( $test ) {
    $name = $test->getName();
    $result = $test->getResult();
    $value = $result->getValue();
    $ex = $result->getException();
    $time = $result->getTimer();
    $output = $result->getOutput();
    $outputStr = empty( $result->getOutput()->get() ) ?
      '(none)' :  "$output";
    
    echo "    $name:\n";
    echo "      value:     $value\n";
    echo "      exception: $ex\n";
    echo "      time:      {$time}s\n";
    echo "      output:    $outputStr\n";
    
    foreach ( $test->getConditions() as $condition ) {
      if ( !$test->getResult()->satisfies( $condition ) )
        echo '      failed:    ' . $condition->getName() . "\n";
    }
  }
  
  foreach ( get_declared_classes() as $class )
    if ( is_subclass_of( $class, '\Sliver\TestSuite' ) )
      $suites[$class] = new $class();
  
  echo "\n";
  $timer->start();
  foreach ( $suites as $suite ) {
    $result = $suite->run();
    displaySuite( $suite, $result );
  }
  $timer->stop();
  echo "\n";
  
  /*
    Compute aggregate figures
  */
  
  $nTests = 0;
  $nTestsPassed = 0;
  $nConditions = 0;
  $nConditionsPassed = 0;
  
  foreach ( $suites as $suite ) {
    foreach ( $suite->getTests() as $test ) {
      $nTests++;
      foreach( $test->getConditions() as $cond ) {
        $nConditions++;
        if ( $test->getResult()->satisfies( $cond ) ) $nConditionsPassed++;
      }
      if ( $test->passed() ) $nTestsPassed++;
    }
  }
  
  echo "  $nTestsPassed / $nTests tests ( $nConditionsPassed / $nConditions "
    . "conditions ) passed in [{$timer}s]\n\n";

  exit( $nTestsPassed === $nTests ? 0 : 1 );
  
?>
