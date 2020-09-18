<?php
  class PharPublisher
  {
    protected $sourceRoot;            # source root directory
    protected $publishRoot;           # publish root directory
    protected $name;                  # file name for PHAR package
    protected $afterEffect;           # executes this after publishing the package
    protected $beforeEffect;          # executes this before publishing the package
    protected $filePattern;           # file filter for package
    protected $defaultStubFileName;   # file for setting the optional default stub
    protected $compressMethod;        # if user wants to compress the files, this will hold the compression method
    protected $workVirtually;

    public function __construct(string $name, string $sourceRoot, string $publishRoot)
    {
      $this->name = $name;
      $this->sourceRoot = $sourceRoot;
      $this->publishRoot = $publishRoot;
      $this->filePattern = NULL;
      $this->defaultStubFileName = 'index.php';
      $this->compressMethod = "NONE";
      $this->workVirtually = false;

      $this->setAfterEffect(
        function () {
          self::consoleLog($this->name." has been successfully published to : ".$this->publishRoot);
        }
      );
    }

    # user function caller method :P
    private static function runEffect($effect)
    {
      if (!empty($effect) && is_callable($effect)) {
        call_user_func_array($effect, []);
      }
    }
    
    # a simple method to echo something to CLI
    private static function consoleLog($message)
    {
      echo PHP_EOL.">> ".$message.PHP_EOL.PHP_EOL;
    }

    # sets a value to whether to use buffering
    public function shouldDoBuffering($value)
    {
      $this->workVirtually = true;
    }

    public function setFilePattern(string $filePattern)
    {
      $this->filePattern = $filePattern;
    }

    public function doGZCompression()
    {
      $this->compressMethod = "GZ";
    }

    # assigns a function before publishing the package
    public function setBeforeEffect($closure)
    {
      if(is_callable($closure)) {
        $this->beforeEffect = $closure;
        return true;
      } else return false; # not a callable
    }
    
    # assigns a function after publishing the package
    public function setAfterEffect($closure)
    {
      if(is_callable($closure)) {
        $this->afterEffect = $closure;
        return true;
      } else return false; # not a callable
    }

    public function setDefaultStub(string $defaultStubFileName)
    {
      $this->defaultStubFileName = $defaultStubFileName;
    }

    # builds the Phar archive and publishes to the given path
    public function publish()
    {
     if (Phar::canWrite()) {

        self::runEffect($this->beforeEffect);
        
        $pharFileName = $this->publishRoot.'/'.$this->name;

        if (is_file($pharFileName)) {
          unlink($pharFileName);
        }
        
        $phar = new Phar($pharFileName, FileSystemIterator::CURRENT_AS_FILEINFO | FileSystemIterator::KEY_AS_FILENAME, $this->name);
        
        if ($this->workVirtually === true) {
          $phar->startBuffering();
        }

        if (!is_null($this->filePattern)) {
          $phar->buildFromDirectory($this->sourceRoot, $this->filePattern);
        } else {
          $phar->buildFromDirectory($this->sourceRoot);
        }
  
        # set the stub
        $defaultStub = $phar->createDefaultStub($this->defaultStubFileName);
        $stub = "#!/usr/bin/php \n".$defaultStub;
        $phar->setStub($stub);

        # do compressing if user wishes
        if (Phar::canCompress(Phar::GZ) && $this->compressMethod === 'GZ') {
          $phar->compressFiles(Phar::GZ);
        }
        
        chmod($pharFileName, 0770);

        if ($this->workVirtually === true) {
          $phar->stopBuffering();
        }

        self::runEffect($this->afterEffect);

      } else {
        self::consoleLog("FAILURE : PHAR is readonly. You must set 'phar.readonly=0' in your php.ini file.");
      }
    }
  }