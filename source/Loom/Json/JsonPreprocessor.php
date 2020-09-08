<?php
	namespace Loom\Json;
	
	use Loom\Json\JsonFile;
	
	class JsonPreprocessor {
    /**
     * Encodes an array into (optionally pretty-printed) JSON
     *
     * @param  mixed  $data    Data to encode into a formatted JSON string
     * @param  int    $options json_encode options (defaults to JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
     * @return string Encoded json
     */
    public static function encode($data, $toPretty = false, $unescapeUnicode = false, $unescapeSlashes = false) {
			if (PHP_VERSION_ID >= 50400) {
				// decide to pretty print or not
				if($toPretty === true) $json = json_encode($data, JSON_PRETTY_PRINT);
				else $json = json_encode($data);
				
				if (false === $json) return false;
				
				# compact brackets to follow recent php versions
				if (PHP_VERSION_ID < 50428 || (PHP_VERSION_ID >= 50500 && PHP_VERSION_ID < 50512) || (defined('JSON_C_VERSION') && version_compare(phpversion('json'), '1.3.6', '<'))) {
					$json = preg_replace('/\[\s+\]/', '[]', $json);
					$json = preg_replace('/\{\s+\}/', '{}', $json);
				}
				return $json;
			}

			$json = json_encode($data);
			if (false === $json) return false; # json error
			if (!$toPretty && !$unescapeUnicode && !$unescapeSlashes) {
				return $json;
			}
			return JsonPreprocessor::format($json, $unescapeUnicode, $unescapeSlashes);
    }
    

    /**
     * Parses json string and returns hash.
     *
     * @param string $json ·· json string
     * @return mixed
     */
    public static function parseJson($json)	{
			if ($json === null)	return false;
			else {
				$decodedJson = json_decode($json, true);
				switch($decodedJson) {
					case null:
						return false;
					default:
						return $decodedJson;
				}
			}
    }
		
		/* this method is contributed to community by Dave Perrett with MIT Licence
		 * daveperrett.com/articles/2008/03/11/format-json-with-php
		 * ...and kinda "remastered" by me :D
		 */
		public static function format($json, $unescapeUnicode, $unescapeSlashes) {
			$result = '';
			$pos = 0;
			$strLen = strlen($json);
			$indentStr = '  ';
			$newLine = "\n";
			$outOfQuotes = true;
			$buffer = '';
			$noescape = true;

			for ($i = 0; $i < $strLen; $i++) {
				// Grab the next character in the string
				$char = substr($json, $i, 1);
				// Are we inside a quoted string?
				if ('"' === $char && $noescape) {
					$outOfQuotes = !$outOfQuotes;
				}

				if (!$outOfQuotes) {
					$buffer .= $char;
					$noescape = '\\' === $char ? !$noescape : true;
					continue;
				} elseif ('' !== $buffer) {
					if ($unescapeSlashes) {
							$buffer = str_replace('\\/', '/', $buffer);
					}
					if ($unescapeUnicode && function_exists('mb_convert_encoding')) {
						// https://stackoverflow.com/questions/2934563/how-to-decode-unicode-escape-sequences-like-u00ed-to-proper-utf-8-encoded-cha
						$buffer = preg_replace_callback('/(\\\\+)u([0-9a-f]{4})/i', function ($match) {
								$l = strlen($match[1]);
								if ($l % 2) {
									$code = hexdec($match[2]);
									// 0xD800..0xDFFF denotes UTF-16 surrogate pair which won't be unescaped
									// see https://github.com/composer/composer/issues/7510
									if (0xD800 <= $code && 0xDFFF >= $code) {
										return $match[0];
									}
									return str_repeat('\\', $l - 1) . mb_convert_encoding(
										pack('H*', $match[2]),
										'UTF-8',
										'UCS-2BE'
									);
								}
								return $match[0];
						}, $buffer);
					}
					$result .= $buffer.$char;
					$buffer = '';
					continue;
				}

				if (':' === $char) {
					// Add a space after the : character
					$char .= ' ';
				} elseif ('}' === $char || ']' === $char) {
					$pos--;
					$prevChar = substr($json, $i - 1, 1);

					if ('{' !== $prevChar && '[' !== $prevChar) {
						// If this character is the end of an element,
						// output a new line and indent the next line
						$result .= $newLine;
						for ($j = 0; $j < $pos; $j++) {
							$result .= $indentStr;
						}
					} else {
						// Collapse empty {} and []
						$result = rtrim($result);
					}
				}
				$result .= $char;
				// If the last character was the beginning of an element,
				// output a new line and indent the next line
				if (',' === $char || '{' === $char || '[' === $char) {
					$result .= $newLine;
					if ('{' === $char || '[' === $char) {
						$pos++;
					}
					for ($j = 0; $j < $pos; $j++) {
						$result .= $indentStr;
					}
				}
			}
			return $result;
		}
	}