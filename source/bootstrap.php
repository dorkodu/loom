<?php
  require_once "Loom/Utils/Psr4Autoloader.php";
  
  use \Loom\Utils\Psr4Autoloader;
  use \Loom\Loom;

  error_reporting(E_ALL);


  $psr4Autoloader = new Psr4Autoloader();
  $psr4Autoloader->usePharMethod("loom.phar");
  $psr4Autoloader->register();
  
  # registering all namespaces used in Loom
  $psr4Autoloader->addNamespace("Loom\\", "Loom/");
  $psr4Autoloader->addNamespace("Loom\\Dependency\\", "Loom/Dependency/");
  $psr4Autoloader->addNamespace("Loom\\Json\\", "Loom/Json/");
  $psr4Autoloader->addNamespace("Loom\\Utils\\", "Loom/Utils/");
  $psr4Autoloader->addNamespace("Loom\\Weaver\\", "Loom/Weaver/");


  # application logic :D
  $loom = new Loom(realpath("."));
  $loom->run();