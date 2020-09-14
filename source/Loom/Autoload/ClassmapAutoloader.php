<?php
  namespace Loom\Autoload;
  
  use Loom\Logger;

  class ClassmapAutoloader
  {
    private $classmap;

    /**
     * Filters a classmap array
     * @param array $classmap a classmap array to filter
     * @return false on failure
     * @return array filtered classmap
     **/
    private static function filterClassmap(array $classmap)
    {
      $filteredClassmap = array();
      foreach ($classmap as $classmapElement) {
        if (is_string($classmapElement) && Logger::isUsefulNode($classmapElement)) {
          array_push($filteredClassmap, $classmapElement);
        }
      }
      return $filteredClassmap;
    }

    /**
     * Parses a classmap array to make it workful :D
     * @param array $pureClassmap description.
     * @return false on failure
     * @return array on success
     **/
    private static function parseClassmap(array $pureClassmap)
    {
      $filteredClassmap = self::filterClassmap($pureClassmap);
      return empty($filteredClassmap) ? false : $filteredClassmap;
    }

    /**
     * @param string $directoryPath directory to look up in.
     * @return array possible files on success
     **/
    private function addFilesFromDirectory($directoryPath)
    {
      if(Logger::isUsefulDirectory($directoryPath)) {
        $srcDir = dir($directoryPath);
        while(gettype($entry = $srcDir->read()) !== "boolean") {
          if($entry == '.' || $entry == '..') {
            continue;
          }

          $node = $directoryPath.'/'.$entry;
          if (is_dir($entry)) {
            $this->addFilesFromDirectory($node);
          } else {
            $this->addFileToClassmap($node);
          }
        }
        $srcDir->close();
        return true;
      } else return false; # not a useful filesystem node
    }

    /**
     * Adds a PHP file to classmap
     * @param string $entryPath the file path to work on
     * @return bool true on success, false on failure
     */
    private function addFileToClassmap($entryPath)
    {
      if (preg_match("@^(.*).php$@", $entryPath, $results)) {
        $this->classmap[$results[1]] = $entryPath;
        return true;
      } else return false;
    }

    /**
     * Adds an entry to class map
     * @param $entryPath path to push to the global class map.
     * @return 
     **/
    public function addClassmapEntry($entryPath)
    {
      if (Logger::isUsefulFile($entryPath)) {
        $this->addFileToClassmap($entryPath);
      } elseif (Logger::isUsefulDirectory($entryPath)) {
        
      } else return false;
    }

    /**
     * Registers a classmap.
     * @param array $classmap a classmap to register.
     **/
    public function register(array $primitiveClassmap)
    {
      $possibleClassmap = self::parseClassmap($primitiveClassmap);
      if (is_array($possibleClassmap)) {
        foreach ($possibleClassmap as $possibleEntry) {
          $this->addClassmapEntry($possibleEntry);
        }

        $autoloader = function ($className) {
          
        };

      } else return false; # given classmap is ugly
    }
  }
  