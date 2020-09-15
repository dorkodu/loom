<?php
  namespace Loom\Autoload;

  # the class for autoloading PSR-4 based on the sample implementation at official PHP-FIG documentation
  class Psr4Autoloader
  {
    protected $prefixes = array();
    
    public function generateRegister()
    {
      return [$this->prefixes, $this->loadClass];
      spl_autoload_register(array($this, 'loadClass'));
    }

    public function addNamespace($prefix, $baseDir, $prepend = false)
    {
      $prefix = trim($prefix, '\\').'\\';
      $baseDir = rtrim(rtrim($baseDir, '/'), DIRECTORY_SEPARATOR).'/';
      
      if(isset($this->prefixes[$prefix]) === false) {
        $this->prefixes[$prefix] = array();
      }

      if ($prepend)
        array_unshift($this->prefixes[$prefix], $baseDir);
      else
        array_push($this->prefixes[$prefix], $baseDir);
    }

    

    private function loadMappedFile($prefix, $relativeClass)
    {
      
    }

    private function requireFile($file)
    {
      if (is_file($file)) {
        require $file;
        return true;
      }
      return false;
    }
  }