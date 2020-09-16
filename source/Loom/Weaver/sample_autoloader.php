<?php
  
  # loom-weaver.php @generated by Loom

  use ClassmapAutoloader;
  use Psr4Autoloader;

  require "loom/Psr4Autoloader.php";
  require "loom/ClassmapAutoloader.php";

  # psr-4 namespace autoloading
  $universalNamespaces = array();
  $psr4Autoloader = new Psr4Autoloader();
  foreach ($universalNamespaces as $namespace => $path) {
    $psr4Autoloader->addNamespace($namespace, $path);
  }

  # classmap autoloading
  $universalClassmap = array();
  $classmapWeaver = new ClassmapAutoloader();
  $classmapWeaver->register($universalClassmap);