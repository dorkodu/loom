<?php
  /**
   * This is an utility to using Regex patterns in a better way.
   * @copyright (C) 2020 Doruk Dorkodu et al.
   * @author Doruk Dorkodu <doruk@dorkodu.com>
   * @license MIT
   **/
  
  namespace Loom\Utils;
  
  class RegexTinkerer
  {
    protected $patterns = array();

    public function setPattern($key, $pattern)
    {
      if (is_string($pattern)) {
        $this->patterns[$key] = $pattern;
      } else return false;     
    }

    public function getPattern($key)
    {
      return array_key_exists($key, $this->patterns) ? $this->patterns[$key] : false; # regex key doesnt exist
    }

    public function isPatternKey($key)
    {
      return array_key_exists($key, $this->patterns);
    }

    public function matchPattern($patternHolder, $content)
    {
      if ($this->isPatternKey($patternHolder))
        return preg_match($this->getPattern($patternHolder), $content);
      else
        return preg_match($patternHolder, $content);
    }
  }
  