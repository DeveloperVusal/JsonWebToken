<?php 

namespace Vudev\JsonWebToken\Algorithm;

use Vudev\JsonWebToken\JWT;

/**
 * Class for OpenSSL algorithms
 */
class OpenSSL {
	/**
	 * Generate signature openssl
	 * 
	 * @param int $algo
	 * @param string $data
	 * @param string $secret
	 * @access public
	 * @static
	 * @return string
	 */
	public static function generateSignature($algo, $data, $privateKey): string
	{
		$signature = '';

		try {
			$sign = openssl_sign($data, $signature, $privateKey, JWT::ALGRORITMS[$algo][3]);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Signing failed: %s', $e->getMessage()));
		}

		if (!$sign) return 'Signing false'.PHP_EOL;

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
	public static function verifySignature($algo, $data, $publicKey, $signature): string
	{
		return (openssl_verify($data, $signature, $publicKey, $algo)) ? true : false;
	}
}