<?php
  namespace Loom\Dependency;

  use Loom\Logger;
  use Loom\Json\JsonFile;
  use Loom\Json\JsonPreprocessor;
  use Loom\Utils\Dorcrypt;

  class DependencyLocker
  {
    /**
     * Tells whether the current state is locked to a known state
     * 
     * @param JsonFile $jsonFile the loom.json file to check for
     * @return bool
     **/
    public static function isCurrentStateLocked(JsonFile $jsonFile)
    {
      $jsonContent = $jsonFile->read();
      if ($jsonContent !== false) {
        $directoryPath = Logger::getDirectoryPath($jsonFile->getPath());
        $lockFilePath = self::getLockFilePath($directoryPath);
        if ($lockFilePath !== false) {
          $persistedState = self::pullLockState($lockFilePath);
          $currentState = self::generateLockHash($jsonContent);
          if (Dorcrypt::compareHash($currentState, $persistedState)) {
            return true;
          } else return false;
        } else return false;
      } else return false;
    }

    /**
     * Returns the path of loom.lock file in given directory
     *
     * @param $directoryPath
     * @return string path of loom.lock file
     * @return false on failure
     */
    private static function getLockFilePath($directoryPath)
    {
      if (Logger::isUsefulDirectory($directoryPath)) {
        $lockFilePath = $directoryPath.'/loom.lock';
        if (Logger::isUsefulFile($lockFilePath)) {
         return $lockFilePath;
        } else return Logger::createFile($lockFilePath) ? $lockFilePath : false;
      } else return false;
    }

    /**
     * Generates a hash from given content. For now it uses Whirlpool hashing algorithm
     *
     * @param string $content
     * @return string hashed content
     * @return false on failure
     */
    private static function generateLockHash($content)
    {
      return Dorcrypt::whirlpool($content);
    }

    /**
     * Gets the lock hash from given file path
     * 
     * @param string $filePath the loom.lock file path
     * @return false when the content is empty or file is useless
     * @return string the content of loom.lock file
     **/
    private static function pullLockState($filePath)
    {
      $hashContent = Logger::getFileContents($filePath);
      if (is_string($hashContent) && !empty($hashContent)) {
        return $hashContent;
      } else return false;
    }

    /**
     * Puts the lock hash to given file path
     * 
     * @param string $hash content that to put in loom.lock
     * @param string $filePath the loom.lock file path
     * 
     * @return false on failure
     * @return true on success
     **/
    private static function pushLockState($hash, $filePath)
    {
      return Logger::putFileContents($filePath, $hash);
    }

    /**
     * Locks the dependency to the current state
     * @param JsonFile $jsonFile the loom.json of the project
     * @return false on failure
     * @return true on success
     */
    public static function lock(JsonFile $jsonFile)
    {
      $jsonContent = $jsonFile->read();
      if ($jsonContent !== false) {
        $directoryPath = Logger::getDirectoryPath($jsonFile->getPath());
        $lockFilePath = self::getLockFilePath($directoryPath);
        if ($lockFilePath !== false) {
          $currentState = self::generateLockHash($jsonContent);
          return self::pushLockState($currentState, $lockFilePath);
        } else return false;
      } else return false;
    }
  }
  