<?php
	namespace Loom;

	class Response {
		protected $type;
		protected $message;
    protected $code;
    
    /**
     * Class constructor.
     */
    public function __construct($type, $message, $code) {
			if(is_string($type) && is_string($message) && is_integer($code)) {
				$this->type = $type;
				$this->message = $message;
				$this->code = $code;
				return true;
			} else return false; # invalidly-typed params
		}
		
		public function getType() {
			return $this->type;
		}
		
		public function getMessage() {
			return $this->message;
		}
		
		public function getCode() {
			return $this->code;
		}
	}