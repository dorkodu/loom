<?php
  error_reporting(E_ALL);

  use Cyudebeam\Utils\DorukReyiz;
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
    echo $temp[count($temp) - 1];
  };

  spl_autoload_register($doruk);

  DorukReyiz::hallet();
