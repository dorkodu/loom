<?php

use Loom\Autoload\Psr4Autoloader;

error_reporting(E_ALL);

  require_once "Psr4Autoloader.php";

  $unix = new Psr4Autoloader();
  $unix->addNamespace("NS1");
  use NS1\NS2\DorukReyiz;

/*
require_once "Utils/Timer.php";

function calculateCallbackRuntime($callback)
{
  if (is_callable($callback)) {
    $timer = new \Loom\Utils\Timer(true);
    $timer->start();
    call_user_func($callback);
    $timer->stop();
    return $timer->passedTime();
  } else return false;
}


echo calculateCallbackRuntime(function () {
  $c = hash_file("whirlpool", "README.md");
});

echo "<br>";

echo calculateCallbackRuntime(function () {
  $c = hash("whirlpool", file_get_contents("README.md"));
});
*/
  $doruk = function ($className) {
    $temp = explode("\\", $className);
    $onlyClassName = $temp[count($temp) - 1];
    require_once $onlyClassName.".php";
    if ($var) {
      
    } else {
      
    }
  };

  spl_autoload_register($doruk);

  DorukReyiz::hallet();
