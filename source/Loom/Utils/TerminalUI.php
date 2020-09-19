<?php
  namespace Loom\Utils;

  use Loom\Utils\CLITinkerer;
  
  class TerminalUI
  {
    public static function bold($message)
    {
      CLITinkerer::write("\033[1m".$message."\033[0m");
    }
    
    public static function underDashedTitle($message)
    {
      CLITinkerer::writeLine("  \033[1m".$message."\033[0m".PHP_EOL."  --------------------------------");
    }

    public static function pipeTitle($message)
    {
      CLITinkerer::writeLine("  | \033[1m".$message."\033[0m |");
    }

    public static function arrowTitle($message)
    {
      CLITinkerer::writeLine("  \033[1m-> ".$message."\033[0m");
    }

    public static function dotTitle($message)
    {
      CLITinkerer::writeLine("  \033[1m.:: ".$message." ::.\033[0m");
    }

    public static function titledParagraph($title, $content)
    {
      self::bold("  ".$title);
      CLITinkerer::breakLine();
      CLITinkerer::writeLine("  ".$content);
    }

    public static function dictionaryEntry($title, $content)
    {
      self::arrowTitle($title);
      CLITinkerer::writeLine("  ".$content);
      CLITinkerer::breakLine();
    }
  }
  