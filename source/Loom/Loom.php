<?php
	namespace Loom;

  use Loom\Dependency\DependencyLocker;
  use Loom\Dependency\DependencyResolver;
  use Loom\Logger;
  use Loom\Utils\PrimitiveTest;
  use Loom\Utils\CLITinkerer;
  use Loom\Utils\TerminalUI;
  use Loom\Json\JsonFile;
  use Loom\Json\JsonPreprocessor;

  class Loom {
    protected $projectDirectory;
    
    /**
     * Class constructor.
     */
    public function __construct($projectDirectory)
    {
      $this->projectDirectory = realpath($projectDirectory);
    }
    
		
		/**
     * Loom's igniter :D
		 */
		public function run() {
      /**
       * checks for updates
       * checks if config is OK
       * checks for loom.json, loom.lock files
       * 
       */
      switch (CLITinkerer::getArgument(1)) {
        case 'about':
          $this->about();
          break;
        case 'help':
          $this->help();
          break;
        case 'init':
          $this->init();
          break;
        case 'weave':
          $this->weave();
          break;
        case 'install':
          $this->install();
          break;
        default:
          $this->greet();
          break;
      }
    }

    /** Prints the logo of Loom */
    public function renderBrand()
    {
      TerminalUI::bold("
  __     _____   _____  __    __
  ||    ||   || ||   || ||\  /||
  ||    ||   || ||   || || \/ ||
  ||___ ||___|| ||___|| ||    ||
  ¨¨¨¨¨  ¨¨¨¨¨   ¨¨¨¨¨  ¨¨    ¨¨
      ");
      CLITinkerer::breakLine();
    }
    
     /**
     * Writes some basic info about Loom
     */
    public function about()
    {
      $this->greet();

      $aboutString = "
  Loom is created to solve autoloading problem in a simple & efficient way.

  You only declare where can Loom find the class, as directory, filename or namespace.
  Loom does the dirty work for you. No magic, just simplicity :D
  You require a file that Loom generated for you, that's it!
      ";

      CLITinkerer::writeLine($aboutString);
      TerminalUI::underDashedTitle("Author");
      TerminalUI::titledParagraph("Doruk Dorkodu", "Software Engineer, Founder & Chief @ Dorkodu".PHP_EOL."  See more 'https://doruk.dorkodu.com".PHP_EOL."  Email : doruk@dorkodu.com".PHP_EOL);
    }

    /**
     * Simply greets users. No magic xD
     */
    public function greet() 
    {
      $this->renderBrand();
      TerminalUI::underDashedTitle("Loom - the Minimalist Dependency Utility for PHP");
      
      $greetString = "
  Loom helps you handle and automate the autoloading process of (in)dependent code bundles in PHP applications.
  See 'https://opensource.dorkodu.com/loom' for further knowledge and documentation.

  Proudly brought you by Dorkodu.
  See how we change the future with Dorkodu @ 'https://dorkodu.com'
  
  Type 'help' to get a list of useful commands.
      ";

      CLITinkerer::writeLine($greetString);
    }

    /**
     * Prints the help page (a simple monolith documentation text) for Loom.
     **/
    public function help()
    {
      $this->greet();
      CLITinkerer::breakLine();
      CLITinkerer::writeLine("  If you are just curious about Loom, type 'about' command to know more.");
      CLITinkerer::breakLine();
      
      # how to use loom ?
      TerminalUI::underDashedTitle("How to use Loom?");
      CLITinkerer::writeLine("  Launch Loom in the directory you want to work in.");
      CLITinkerer::writeLine("  For the first time in that directory, use 'init' command.");
      CLITinkerer::writeLine("  It will set the environment, and create some files/directories.");
      CLITinkerer::writeLine("  Then you declare your depencencies as classmap or as PSR-4 namespaces, with JSON format, in 'loom.json' file.");
      CLITinkerer::writeLine("  When you are done, run 'weave' command.");
      CLITinkerer::writeLine("  Loom will 'weave' your dependencies and create you a file './loot/loom-weaver.php' that you can require in your script.");
      CLITinkerer::writeLine("  That's it! Don't forget to run 'weave' after everytime you manipulate 'loom.json' file.");
      CLITinkerer::breakLine();

      # useable commands list
      TerminalUI::underDashedTitle("Possible Actions");
      CLITinkerer::writeLine("  List of available commands :");
      CLITinkerer::breakLine();
      TerminalUI::dictionaryEntry("install", "Installs Loom, so you can use directly typing 'loom <command>' in terminal.");
      TerminalUI::dictionaryEntry("init", "Loom will prepare the project directory for its operations. create some files/directories for its needs.");
      TerminalUI::dictionaryEntry("about", "You can learn more about Loom. It's recommended to read some stuff :D");
      TerminalUI::dictionaryEntry("weave", "Loom interprets the loom.json file and 'weaves' your dependencies to your app. It produces a loom-weaver.php file that you require in your app, and forget about autoloading hell!");
      TerminalUI::dictionaryEntry("help", "The simple documentation on Loom, which is exteremely useful. You are reading it now :) But you don't need to worry about underlying logic. It is not magic, created by a 16 yo software engineer :D");
      CLITinkerer::breakLine();

      # stuff related to Loom
      TerminalUI::underDashedTitle("What Loom does in my directory?");
      TerminalUI::dictionaryEntry("loom.json", "The file you declare your dependencies in JSON format.");
      TerminalUI::dictionaryEntry("loom.lock", "Loom will save its last run state in this file.");
      TerminalUI::dictionaryEntry("loot/ directory", "You can put your dependent bundles (files/directories) in this directory. Loom uses 'loot/' for storing its app-related files. It can be used as a single repository of vendor-lock-in code.");
      TerminalUI::dictionaryEntry("loot/loom-weaver.php", "Loom's monolithic/minimalistic autoloading script. You just require this file in your app. It will do the dirty work for you :D");
      CLITinkerer::breakLine();
    }

    /**
     * Initialises the environment for Loom, in given directory.
     **/
    public function init()
    {
      /**
       * if not already used by Loom, then :
       * -> CREATE :
       *    - an empty template loom.json
       *    - generate that template's  hash and lock the state into loom.lock
       */
       if (Logger::isUsefulDirectory($this->projectDirectory)) {
          if ($this->isInittedDirectory($this->projectDirectory)) {
            self::consoleLog("Already initted directory.");
            return true;
          } else {
            # dir is useful
            # loom.json production
            $loomJsonPath = $this->projectDirectory."/loom.json";
            if (Logger::createFile($loomJsonPath)) {
              
              $loomJson = new JsonFile($loomJsonPath);
              unset($loomJsonPath);
              $loomJsonTemplate = $this->generateLoomJsonTemplate();
              $loomJson->write($loomJsonTemplate, true);

              if(DependencyLocker::lock($loomJson)) {
                self::consoleLog("Loom successfully initialised the current directory.");
                return true;
              } else {
                self::throwIssue("PROBLEM", "Couldn't lock the current state.");
              }
            } else {
              self::throwIssue("PROBLEM", "Couldn't create loom.json file.");
            }
          }
       } else {
        self::throwIssue("PROBLEM", "Current directory is not useful. Check read/write permissions.");
       }
    }

    /**
     * Checks if the directory is already processed by Loom
     *
     * @return boolean
     */
    public function isInittedDirectory()
    {
      if (Logger::isUsefulFile($this->projectDirectory."/loom.json") && Logger::isUsefulFile($this->projectDirectory."/loom.lock")) {
        return true;
      } else {
        return false; # no loom.json || loom.lock file ?!
      }
    }

    /**
     * Weaves the dependencies in given directory.
     **/
    public function weave()
    {
      /**
       * read loom.json and loom.lock, compare states :
       * 
       * --if state is changed
       *    -> create loot/ and fill it
       *    -> generate a fresh autoloading script, and lock the state.
       * --else
       *    -> dont touch it :P
       */
      if (Logger::isUsefulDirectory($this->projectDirectory)) {
        self::consoleLog("Directory is useful. Loom is running.");
        
        if ($this->isInittedDirectory()) {
          self::consoleLog("Current directory is initted. Look for dependency declarations.");
          
          $loomJson = new JsonFile($this->projectDirectory."/loom.json");
          DependencyLocker::isCurrentStateLocked($loomJson);
          if ($loomJson->) {
            # code...
          } else $this->throwIssue("PROBLEM", "'loom.json' is not useful. Check read/write permissions. OR file is not useful.");
        } else $this->throwIssue("PROBLEM", "Current directory is not initted. Please run 'init' before.");  
      } else self::throwIssue("PROBLEM", "Current directory is not useful. Check for read/write permissions.");
    }

    /**
     * Weaves the dependencies in given directory.
     **/
    public function install()
    {
      /**
       * read loom.json and loom.lock, compare states : 
       * if state is changed generate a fresh autoloading script, and lock the state.
       * else dont touch it :P
       */
      self::throwIssue("NOTICE", "This feature is out of order.");
    }

    public static function consoleLog($text)
    {
      CLITinkerer::writeLine("> ".$text);
    }

    public static function throwIssue($type, $message)
    {
      CLITinkerer::writeLine("> ".$type." : ".$message);
      exit;
    }

    /**
     * Generates an empty, template string for loom.json
     * 
     * @return array the template string content of a loom.json file
     **/
    public function generateLoomJsonTemplate()
    {
      $classmap = array();
      $namespaces = array();
      return array("knotted" => array("classmap" => $classmap, "namespaces" => $namespaces));
    }
	}