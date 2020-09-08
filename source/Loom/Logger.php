<?php
	namespace Loom;
  
  # Logger is a utility for interacting with FileSystem. It handles file / directory operations.
	class Logger {
		
		public static function isUsefulDirectory($dirPath) {
			if(is_dir($dirPath) && is_readable($dirPath) && is_writable($dirPath)) {
				return true;
			} else return false;
		}
		
		public static function isUsefulFile($filePath) {
			if(is_file($filePath) && is_readable($filePath) && is_writable($filePath)) {
				return true;
			} else return false;
    }
    
    public static function isUsefulNode($path) {
			if(self::isUsefulFile($path) || self::isUsefulDirectory($path)) {
				return true;
			} else return false;
		}
		
		/** 
		 * @return boolean
		 */
		public static function createDirectory($directory, $permissions = 0777) {
			if(!empty($directory)) {
				# is /loot/ dir exists and useful? if no create it
				if(self::isUsefulDirectory($directory)) {
					return true;
				} else {
					# try to create the directory
					if(mkdir($directory, $permissions, true) && self::isUsefulDirectory($directory))
						return true;
					else return false; # could not make the dir
				}
			} else return false; # project directory is empty
		}
		
    /**
     * Creates a useful file with 0777 permissions
     *
     * @param $file
     * @return true on success
     * @return false on failure
     */
		public static function createFile($filePath) {
      if(is_string($filePath)) {
        # does file exist and useful? if no create it
        if(self::isUsefulFile($filePath))
          return true;
        else {
          # try to create the file
          if(touch($filePath) && self::isUsefulFile($filePath)) {
            return true;
          } else return false; # could not create the file
        }
      } else return false; # file path is not a string
    }
    
    /**
     * Removes a useful file
     *
     * @param $filePath
     * @return true on success
     * @return false on failure
     */
		public static function removeFile($filePath) {
      if(self::isUsefulFile($filePath) === false) {
        return true;
      } else {
        if(unlink($filePath) && self::isUsefulFile($filePath) === false) {
          return true;
        } else return false; # something went wrong when deleting file
      }
    }
    
    /**
     * Reads entire file content into a string, if file is useful
     * 
     * @param string $filePath
     * @return string entire content of the file
     * @return false on failure
     **/
    public static function getFileContents($filePath)
    {
      if (self::isUsefulFile($filePath)) {
        return file_get_contents($filePath);
      } else return false;
    }

    /**
     * Writes entire content into file, if file is useful
     * 
     * @param string $filePath
     * @param string $content
     * @return true on success
     * @return false on failure
     **/
    public static function putFileContents($filePath, $content)
    {
      if (self::isUsefulFile($filePath)) {
        return is_int(file_put_contents($filePath, $content));
      } else return false;
    }

    /**
     * Copy everything in a path, into a given directory
     * 
     * @param string $source
     * @param string $targetDir
     * @return boolean true on success, false on failure
     */
		public static function copyEverything($source, $targetDir) {
			if(self::isUsefulDirectory($targetDir)) {
				if(self::isUsefulDirectory($source)) {
					if(!file_exists($targetDir)) { mkdir($targetDir, 0777); }
					$srcDir = dir($source);
					while(gettype($entry = $srcDir->read()) !== "boolean") {
						if($entry == '.' || $entry == '..')
							continue;
						$node = $source.'/'.$entry;
						if(is_dir($node)) {
							self::copyEverything($node, $targetDir.'/'.$entry);
							continue;
						}
						copy($node, $targetDir.'/'.$entry);
					}
					$srcDir->close();
					return true;
				} else if(self::isUsefulFile($source)) {
					copy($source, $targetDir);
					return true;
				} else return false; # not a useful filesystem node
			} else return false; # invalid parameters
    }
    
    public static function getDirectoryPath($path)
    {
      if (self::isUsefulNode($path)) {
        return realpath(dirname($path));
      } else return false;
    }
  }
