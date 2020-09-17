<?php
  class CLITinkerer
  {
    public static function readLine($optionalMessage = null)
    {
      $fh = fopen('php://stdin', 'r');
      if (!empty($optionalMessage))  {
        echo $optionalMessage." ";        
      }
      $userInput = trim(fgets($fh));
      fclose($fh);
      unset($fh);

      return $userInput;
    }
    
    public static function writeLine($message)
    {
      echo $message.PHP_EOL;
    }

    public static function getScriptName()
    {
      return $_SERVER['argv'][0];
    }

    public static function argumentCount()
    {
      return $_SERVER['argc'];
    }

    public static function getArgument($index)
    {
      if (is_int($index)) {
        if(isset($_SERVER['argv'][$index]) && !empty($_SERVER['argv'][$index])) {
          return $_SERVER['argv'][$index];
        } else return false;
      } else return false;
    }
  }
  