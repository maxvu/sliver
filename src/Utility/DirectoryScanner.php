<?php

  namespace Sliver\Utility;
  
  use RecursiveIteratorIterator;
  use RecursiveDirectoryIterator;
  use RegexIterator;

  class DirectoryScanner {
    
    private $pattern;
    private $onlyFiles;
    private $onlyReadable;
    
    public function __construct (
      $pattern = "/.*/",
      $onlyFiles = TRUE,
      $onlyReadable = TRUE
    ) {
      $this->pattern = $pattern;
      $this->onlyFiles = $onlyFiles;
      $this->onlyReadable = $onlyReadable;
    }
    
    public function scan ( $dir ) {
      $dir = realpath( $dir );
      $it = new RecursiveDirectoryIterator( $dir );
      $it = new RecursiveIteratorIterator( $it );
      $it = new RegexIterator( $it, $this->pattern );
      $result = [];
      
      foreach ( $it as $name => $file ) {
        if ( !$file->isFile() && $this->onlyFiles ) continue;
        if ( !$file->isReadable() && $this->onlyReadable ) continue;
        $result[] = $name;
      }
      
      return $result;
    }

  };

?>