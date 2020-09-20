<?php
  namespace Loom\Dependency;

  use Loom\Json\JsonFile;
  use Loom\Json\JsonPreprocessor;
  use Loom\Logger;

  class DependencyResolver
  {
    /**
     * Returns the knotted array for a given package
     *
     * @param array $rootArray the root array of loom.json
     * 
     * @return false on failure
     * @return array the required array for package
     */
    private static function getKnottedArray(array $rootArray)
    {
      if (!empty($rootArray)) {
        return self::getArrayFromArray("knotted", $rootArray);
      } else return false; # stupid required array
    }

    /**
     * Returns an array element from an array
     * 
     * @return false on failure
     * @return array on success
     */
    private static function getArrayFromArray($needle, array $haystack)
    {
      if (array_key_exists($needle, $haystack)) {
        if (is_array($haystack[$needle])) {
          return $haystack[$needle]; # returns if a desired array
        } else {
          return array($haystack[$needle]); # puts in an array, if not an array lol :P 
        }
      } else return false; # not a desired array
    }

    /**
     * Parses the 'knotted' attribute of the root array of loom.json and 
     * returns a meaningful knotted's array
     * 
     * @param array $jsonAssocArray
     */
    private static function resolveKnotteds(array $jsonAssocArray)
    {
      $knotteds = self::getKnottedArray($jsonAssocArray);
      if ($knotteds !== false) {
        $namespacesList = self::getArrayFromArray('namespaces', $knotteds);
        $classmap = self::getArrayFromArray('classmap', $knotteds);

        if ($namespacesList === false)
          $knotteds["namespaces"] = array();
        if ($classmap === false)
          $knotteds["classmap"] = array();
        return $knotteds;
      } else return false; # at any error
    }
  
    /**
     * Resolves dependencies for a given package
     * 
     * @param JsonFile jsonFile object for loom.json file
     * 
     * @return array of knotted array.
     * @return false on failure
     */
    public static function resolve(JsonFile $jsonFile)
    {
      if ($jsonFile->isUseful()) {
        $jsonContent = $jsonFile->read();
        
        if ($jsonContent !== false) {

          $jsonArray = JsonPreprocessor::parseJson($jsonContent);
          $rootArray = array();

          $knottedArray = self::resolveKnotteds($jsonArray);

          if ($knottedArray !== false) {
            $rootArray["knotted"] = $knottedArray;
          }

          return $rootArray;
        } else return false;
      } else return false;
    }
  }