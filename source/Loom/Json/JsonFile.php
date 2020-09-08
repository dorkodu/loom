<?php
	namespace Loom\Json;
	
	use Loom\Logger;
	use Loom\Json\JsonPreprocessor;
	
	class JsonFile {
    
    protected $path;
		
		public function __construct($path) {
			$this->path = $path;
    }
		
		public function getPath() {
			return $this->path;
    }
    
    public function isUseful() {
      return Logger::isUsefulFile($this->path);
    }
		
    public function read() {
			$json = file_get_contents($this->path);
			if($json !== null || $json !== false) {
				return $json;
			} else return false; # problem with reading the json file
    }

    /** Writes json file.
		 * @param  array $hash ·· writes hash into json file
     * @param  int 	 $options ·· json_encode options (defaults to JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
     */
    public function write(array $hash, $options = 448) {
			if ($this->path === 'php://memory') {
				Logger::putFileContents($this->path, JsonPreprocessor::encode($hash, $options));
				return;
			}
			$dir = dirname($this->path);
			if (!is_dir($dir)) {
				if (file_exists($dir)) return false; # it exists and not a directory
				if (!@mkdir($dir, 0777, true)) return false; # it does not exists and could not be created
			}
			$retries = 3;
			while ($retries--) {
				try {
					$this->putContentsIfModified($this->path, JsonPreprocessor::encode($hash, $options));
					break;
				} catch (\Exception $e) {
					if ($retries) {
						usleep(500000);
						continue;
					}
					throw $e;
				}
			}
    }

    /**
     * modify file properties only if content modified
     */
    private function putContentsIfModified($path, $content)
    {
        $currentContent = Logger::getFileContents($path);
        if (!$currentContent || ($currentContent != $content)) {
            return Logger::putFileContents($path, $content);
        }
        return 0;
    }
	}