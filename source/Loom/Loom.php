<?php
	namespace Loom;
	
	use Loom\Logger;
  use Loom\Utils\PrimitiveTest;
	
	class Loom {
		protected $loot;
		protected $projectDirectory;
		
		public function __construct() {
			$this->loot = new Loot();
		}
		
		/* name: run
		 * @param string : file path to work in it.
		 * @return boolean (as i hope :D)
		 */
		public function run($projectDirectory) {
			$this->projectDirectory = realpath($projectDirectory);
			if($this->setTheEnvironment()) {
				PrimitiveTest::consoleLog("Environment is ready");
				/* checks for updates
				 * checks if config is OK
				 * checks for /loot directory and loom.json, loom.lock files
				 * */
			} else PrimitiveTest::consoleLog("Cannot set the environment"); # cannot setup the environment
		}
		
		/* @param string : package name to knot on project
		 * @return boolean
		 * if project isn't configured then do that.
		 * else resolve the name 
		 * and use the loot to knot the package to the project.
		 * */
		public function knotPackageByPackageDirectory($packageDir) {
			PrimitiveTest::consoleLog("The told package is : $packageDir");
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
	}