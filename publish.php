<?php
  error_reporting(E_ALL);
  require "PharPublisher.php";

  $greet = function () {
    echo PHP_EOL.">> Dorkodu Phar Publisher";
  };

  $simplestPhar = new PharPublisher('loom.phar', './source', './publish');
  $simplestPhar->setBeforeEffect($greet);
  $simplestPhar->publish();
