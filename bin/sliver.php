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
    Instantiate, enumerate, filter to subclasses of \Sliver\TestSuite.
  */
  
  $controller = new \Sliver\ConsoleTestController();
  
  foreach ( get_declared_classes() as $class ) {
    try {
      if ( is_subclass_of( $class, '\Sliver\TestSuite' ) )
        $controller->addSuite(new $class());
    } catch ( Exception $e ) {
      echo "Exception caught when adding testsuite class $class:\n\t";
      echo $e->getMessage() . "\n";
      exit(1);
    }
  }
  
  /*
    Run and exit().
  */

  exit( $controller->run()->allPassed() ? 0 : 1 );
  
?>
