<?php
  namespace Loom\Utils;

  use Loom\Utils\Timer;
  
  class PrimitiveTest
  {
    public static function see($objective)
    {
      echo "<br><pre style='display:inline-block; padding:10px; background-color:#ddeeff; border-top:3px solid cadetblue; color:#556677;'>";
      var_dump($objective);
      echo "</pre>";
    }

    public static function consoleLog($outputText)
    {
      echo "<p style='font-family:monospace; font-size:15px;'><span style='color:cadetblue;'>loom $ </span>".$outputText."</p>";      
    }

    public static function calculateCallbackRuntime($callback)
    {
      if (is_callable($callback)) {
        $timer = new Timer(true);
        $timer->start();
        call_user_func($callback);
        $timer->stop();
        return $timer->passedTime();
      } else return false;
    }
  }
  