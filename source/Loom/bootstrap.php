<?php


error_reporting(E_ALL);


require_once "Weaver/ClassmapAutoloader.php";

/*
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
echo PHP_EOL;
$cmap = array(".");

$c = new ClassmapAutoloader();
$c->register($cmap);

if (\Loom\Logger::isUsefulFile("d.php")) {
  echo "All my loving :D";
} else {
  echo "I should have known better";
}