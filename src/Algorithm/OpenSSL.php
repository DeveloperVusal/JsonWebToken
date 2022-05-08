<?php 

namespace Vudev\JsonWebToken\Algorithm;

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
			$sign = openssl_sign($data, $signature, $privateKey, constant('\Vudev\JsonWebToken\Algorithms::'.$algo)[2]);
		} catch (\Exception $e) {
			throw new \Exception(sprintf('Signing failed: %s', $e->getMessage()));
		}

		if (!$sign) return 'Signing false'.PHP_EOL;

		return bin2hex($signature);
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
		return (openssl_verify($data, hex2bin($signature), $publicKey, constant('\Vudev\JsonWebToken\Algorithms::'.$algo)[2])) ? true : false;
	}
}