#!/usr/bin/php
<?php

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
    if ( is_readable( $file[0] ) )
      require_once $file[0];
  }
  
  $controller = new \Sliver\ConsoleTestController();
  
  foreach ( get_declared_classes() as $class ) {
    if ( is_subclass_of( $class, '\Sliver\TestSuite' ) )
      $controller->addSuite(new $class());
  }
  
  $controller->run();
  
  if ( $controller->allPassed() )
    exit(0);
  
  if ( sizeof( $controller->getSuites() ) == 0 )
    echo "No tests found. (Is the tests directory reachable from here?)\n";
  
  exit(1);
  
?>
