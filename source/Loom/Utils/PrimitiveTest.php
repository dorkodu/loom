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

    public static function memoryPeak()
    {
      return self::formatBytes(memory_get_peak_usage());
    }

    public static function formatBytes($bytes, $precision = 2)
    {
      $units = array("B", "kB", "MB", "GB", "TB");
  
      $bytes = max($bytes, 0);
      $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
      $pow = min($pow, count($units) - 1);
      $bytes /= (1 << (10 * $pow));
  
      return round($bytes, $precision) . " " . $units[$pow];
    }
  }
  