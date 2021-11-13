<?php 

namespace Vudev\JsonWebToken\Algorithm;

/**
 * Encode and Decode data with base64
 */
class Base64 {
	/**
	 * Encode base64
	 * 
	 * @param string $data
	 * @access public
	 * @static
	 * @return string
	 */
	public static function encode(string $data): string
	{
		return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
	}

	/**
	 * Decode base64
	 * 
	 * @param string $data
	 * @access public
	 * @static
	 * @return string
	 */
	public static function decode(string $data): string
	{
		return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
	}
}