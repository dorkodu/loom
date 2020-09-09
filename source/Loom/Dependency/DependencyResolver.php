<?php
  namespace Loom\Dependency;

  use Loom\Json\JsonFile;
  use Loom\Json\JsonPreprocessor;
  use Loom\Logger;

  class DependencyResolver
  {
    /**
     * Returns the knotted array for a given package
     * @param JsonFile $jsonFile
     * @return false on failure
     * @return array the required array for package
     */
    protected static function getKnottedArray($knottedArray)
    {
      if (is_array($knottedArray) && !empty($knottedArray)) {
        return $knottedArray;
      } else return false; # stupid required array
    }

    /**
     * Returns an array element from an array
     * @return false on failure
     * @return array on success
     */
    protected static function getArrayFromArray($needle, array $haystack)
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
     * Parses the knotted attribute of an assoc array of loom.json and 
     * returns a meaningful autoload's list for that package
     * @param array $jsonAssocArray
     */
    public static function resolveKnotteds(array $jsonAssocArray)
    {
      $knotteds = self::getKnottedArray($jsonAssocArray);
      if ($knotteds !== false) {
        $namespacesList = self::getArrayFromArray('namespaces', $knotteds);
        $classmap = self::getArrayFromArray('classmap', $knotteds);
        $results = array();
        if (is_array($namespacesList) && !empty($namespacesList))
          array_push($results, $namespacesList);
        if (is_array($classmap) && !empty($classmap))
          array_push($results, $classmap);
        return $results;
      } else return false; # at any error
    }
  
    /**
     * Resolves dependencies for a given package
     * @param JsonFile jsonFile object for loom.json file
     * @return array of knotted array.
     * @return false on failure
     */
    public static function resolve(JsonFile $jsonFile)
    {
      if ($jsonFile->isUseful()) {
        $jsonContent = $jsonFile->read();
        if ($jsonContent !== false) {
          $jsonAssocArray = JsonPreprocessor::parseJson($jsonContent);
          $knottedsArray = self::resolveKnotteds($jsonAssocArray);
        } else return false;
      } else return false;
    }
  }