<?php
  namespace Loom\Autoload;
  
  use Loom\Logger;

  class ClassmapAutoloader
  {
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
    private static function listPossibleFilesInDirectory($directoryPath)
    {
      if(Logger::isUsefulDirectory($directoryPath)) {
        $possibleFiles = array();

        $srcDir = dir($directoryPath);
        while(gettype($entry = $srcDir->read()) !== "boolean") {
          if($entry == '.' || $entry == '..' || is_dir($entry)) {
            continue;
          }
          $node = $directoryPath.'/'.$entry;
          if (preg_match("@^(.*).php$@", $entry, $results)) {
            $filePath = $node;
            $fileName = $results[1];
            $possibleFiles[$fileName] = $filePath; 
          }
        }
        $srcDir->close();
        return $possibleFiles;
      } else return false; # not a useful filesystem node
    }

    /**
     * Registers a classmap.
     *
     * @param array $classmap a classmap to register.
     * @return type
     **/
    public static function register(array $classmap)
    {
      
    }
  }
  