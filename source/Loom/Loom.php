<?php
	namespace Loom;
	
	use Loom\Logger;
  use Loom\Utils\PrimitiveTest;
  use Loom\Utils\CLITinkerer;
  use Loom\Utils\TerminalUI;
	
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
			if(true) {
				/**
         * checks for updates
				 * checks if config is OK
				 * checks for loom.json, loom.lock files
         * 
         */
       $this->route();
      } else {  # cannot setup the environment
        CLITinkerer::writeLine("Cannot set the environment. Check if current directory is useful");
      }
    }
		
    /**
     * Simply routes according to user commands.
     **/
    public function route()
    {
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
        default:
          $this->greet();
          break;
      }
    }
		
		public function setTheEnvironment() {
			# check for whether environment is already set
			if($this->isEnvironmentReady()) {
				return true; # project is already using Loom
			} else {
				if(Logger::isUsefulDirectory($this->projectDirectory)) {
					if(Logger::createDirectory($this->projectDirectory."/loot") && Logger::createFile($this->projectDirectory."/loom.json") && Logger::createFile($this->projectDirectory."/loom.lock")) {
						return true;
					} else return false; # cannot create the required directory or files
				} else return false; # cannot open the directory	
			}
		}
		
		/* name: isEnvironmentReady
		 * @return boolean
		 * check for if the project is already using Loom
		 */
		public function isEnvironmentReady() {
			if(!empty($this->projectDirectory)) {
				if(Logger::isUsefulDirectory($this->projectDirectory)) {
					# look for : /loot, loom.json, loom.lock
					if(Logger::isUsefulDirectory($this->projectDirectory."/loot") && Logger::isUsefulFile($this->projectDirectory."/loom.json") && Logger::isUsefulFile($this->projectDirectory."/loom.lock"))
						return true;
					else return false; # required stuff doesnt exist
				} else return false; # project directory is not usable
			} else return false; # projectDir is empty
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
    public function greet() {
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
      CLITinkerer::writeLine("If you are just curious about Loom, type 'about' command to know more.");
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

      # useable commands list
      TerminalUI::underDashedTitle("Possible Actions");
      CLITinkerer::writeLine("  List of available commands :");
      CLITinkerer::breakLine();
      TerminalUI::dictionaryEntry("install", "Installs Loom, so you can use directly typing 'loom <command>' in terminal.");
      TerminalUI::dictionaryEntry("init", "Loom will prepare the project directory for its operations. create some files/directories for its needs.");
      TerminalUI::dictionaryEntry("about", "You can learn more about Loom. It's recommended to read some stuff :D");
      TerminalUI::dictionaryEntry("weave", "Loom interprets the loom.json file and 'weaves' your dependencies to your app. It produces a loom-weaver.php file that you require in your app, and forget about autoloading hell!");
      TerminalUI::dictionaryEntry("help", "The simple documentation on Loom, which is exteremely useful. You are reading it now :) But you don't need to worry about underlying logic. It is not magic, created by a 16 yo software engineer :D");

      # stuff related to Loom
      TerminalUI::underDashedTitle("What Loom does in my directory?");
      TerminalUI::dictionaryEntry("loom.json", "The file you declare your dependencies in JSON format.");
      TerminalUI::dictionaryEntry("loom.lock", "Loom will save its last run state in this file.");
      TerminalUI::dictionaryEntry("loot/ directory", "You can put your dependent bundles (files/directories) in this directory. Loom uses 'loot/' for storing its app-related files. It can be used as a single repository of vendor-lock-in code.");
      TerminalUI::dictionaryEntry("loot/loom-weaver.php", "Loom's monolithic/minimalistic autoloading script. You just require this file in your app. It will do the dirty work for you :D");
    }

    /**
     * Initialises the environment for Loom, in given directory.
     **/
    public function init()
    {
      /**
       * create :
       * an empty template loom.json
       * generate that template's  hash and lock the state into loom.lock
       */
      CLITinkerer::writeLine("inits the Loom");
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
      CLITinkerer::writeLine("weaved!");
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
      CLITinkerer::writeLine("install you mean?");
    }
	}