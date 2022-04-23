<?php 

namespace Vudev\JsonWebToken\Algorithm;

/**
 * Class for Hash hmac algorithms
 */
class HashHmac {
	/**
	 * Generate signature hmac
	 * 
	 * @param int $algo
	 * @param string $data
	 * @param string $secret
	 * @access public
	 * @static
	 * @return string
	 */
	public static function generateSignature($algo, $data, $secret): string
	{
		$signature = '';

		try {
			$signature = hash_hmac(constant('\Vudev\JsonWebToken\Algorithms::'.$algo)[1], $data, $secret);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Signing failed: %s', $e->getMessage()));
			
		}

		return $signature;
	}
	
	/**
	 * Verify signatures hmac
	 * 
	 * @param int $algo
	 * @param string $data
	 * @param string $secret
	 * @param string $signature
	 * @access public
	 * @static
	 * @return string
	 */
	public static function verifySignature($algo, $data, $secret, $signature): string
	{
		$sign = '';

		try {
			$sign = hash_hmac(constant('\Vudev\JsonWebToken\Algorithms::'.$algo)[1], $data, $secret);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Signing failed: %s', $e->getMessage()));
		}

		return (hash_equals($sign, $signature)) ? true : false;
	}
}