<?php
  error_reporting(E_ALL);
  require "PharPublisher.php";

  $greet = function () {
    echo PHP_EOL.">> Dorkodu Phar Publisher";
    echo PHP_EOL.">> This code will build and publish Loom.";
  };

  $simplestPhar = new PharPublisher('loom.phar', './source', './publish');
  $simplestPhar->setBeforeEffect($greet);
  $simplestPhar->setDefaultStub("bootstrap.php");
  $simplestPhar->publish();

  $timestamp = time();
  rename("publish/loom.phar", "publish/loom_".$timestamp.".phar");
  echo PHP_EOL.">> Newest Loom is :: loom_".$timestamp.".phar";
