<?php
  namespace Loom\Autoload;
  
  use Loom\Logger;

  class ClassmapAutoloader
  {

    /**
     * Undocumented function.
     *
     * @param  $ description.
     *
     * @return type
     **/
    public function filterClassmap(array $classmap)
    {
      # code...
    }

    /**
     * Undocumented function.
     * @param  $ description.
     * @return type
     **/
      public function parseClassmap(array $classmapArray)
      {
        $filteredClassmap = self::filterClassmap(); 
      }
  }
  