<?php

  namespace Sliver\Runner;
  
  /*
    Composer doesn't have great documentation about its classmap-generation
    magic and a full recursive-include might be dangerous. We don't want to 
    need crazy symbols (looking at you, PHPDoc) to signal test cases, but we 
    need a zero-setup entry-point -- pre-autoload -- to find them.
    
    We'll compromise by scanning, looking for symbols referring to Sliver.
    If a PHP file has tokens the "Sliver", backslash, "Test", then we'll 
    include() it and grep all loaded classes for true extension. This should 
    work for both "use" and absolute-namespace extensions.
    
    I'm not proud of this, but it works.
  */
  
  class TestSuiteSniffer {
    
    private $file;
    
    public function __construct ( $file ) {
      $this->file = $file;
    }
    
    public function smells () {
      $tokens = token_get_all( file_get_contents( $this->file ) );
      for ( $i = 0; $i < sizeof( $tokens ); $i++ ) {
        if (
          isset( $tokens[$i+1] ) &&
          isset( $tokens[$i+2] ) &&
          $tokens[$i][0] === 310 &&
          $tokens[$i][1] === 'Sliver' &&
          $tokens[$i+1][0] === 388 &&
          $tokens[$i+2][0] === 310 &&
          $tokens[$i+2][1] === 'TestSuite'
        ) {
          return TRUE;
        }
      }
      return FALSE;
    }
    
  }

?>