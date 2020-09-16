<?php
  namespace Loom\Weaver;

  use Loom\Logger;
  use Loom\Dependency\DependencyResolver;
  use Loom\Dependency\DependencyLocker;
  
  class KnotterGenerator
  {

    /**
     * Generates content for loom-weaver.php
     * 
     * @param array $namespaces the PSR-4 namespaces array.
     * @param array $classmap the classmap array.
     * 
     * @return string generated loom-weaver.php file content
     * @return false on failure
     **/
    public static function generate(array $namespaces, array $classmap)
    {
      
    }

    /**
     * Gets the contents of the classmap autoloader script.
     * @return string script content
     **/
    private function getClassmapAutoloaderContent()
    {
      $contents = Logger::getFileContents("ClassmapAutoloader.php");
      if (is_string($contents) && $contents !== false) {
        
      } else return false;
    }

    /**
     * Gets the contents of the PSR-4 autoloader script.
     * @return string script content
     **/
    private static function getPsr4AutoloaderContent()
    {
      $contents = Logger::getFileContents("Psr4Autoloader.php");
    }
    
    public static function generateKnotterFile()
    {

    }
  }
  