<?php 
declare(strict_types = 1); // Enabling strong typing

namespace Vudev\JsonWebToken;

use Vudev\JsonWebToken\Algorithm\HashHmac;
use Vudev\JsonWebToken\Algorithm\OpenSSL;
use Vudev\JsonWebToken\Algorithm\Base64;
use Vudev\JsonWebToken\DateConvert;

/**
 * Create JWT pair tokens
 * 
 * @author Mamedov Vusal
 */
class JWT {
	/**
	 * Dynamic payload
	 * 
	 * Most likely will change with each release
	 * 
	 * @var array
	 */
	const dynamic_payload = [
		'expiresIn'
	];

	/**
	 * Header JWT Token
	 * 
	 * @access protected
	 * @var array
	 */
	protected array $header = [
						'alg' => 'HS256',
						'typ'=> 'JWT'
					];

	/**
	 * Payload JWT Token
	 * 
	 * @access protected
	 * @var array
	 */
	protected array $payload = [];

	/**
	 * Secret key for HMAC
	 * 
	 * @access protected
	 * @var string
	 */
	protected string $secret;

	/**
	 * Public key for OpenSSL
	 * 
	 * @access protected
	 * @var object|string
	 */
	protected $public_key;

	/**
	 * Private key for OpenSSL
	 * 
	 * @access protected
	 * @var object|string
	 */
	protected $private_key;

	/**
	 * Generate hash type algorithm
	 * 
	 * @access protected
	 * @var string
	 */
	protected $hash_algo;

	/**
	 * Generate hash type algorithm
	 * 
	 * @param array $options
	 * @access public
	 * @return void
	 */
	public function __construct(array $options)
	{
		if (isset($options['header']) && sizeof($options['header'])) $this->header = $options['header'];

		if (isset($options['payload']) && sizeof($options['payload'])) $this->payload = $options['payload'];
		if (isset($options['secret']) && !empty($options['secret'])) $this->secret = $options['secret'];

		$this->hash_algo = strtoupper((!empty($this->header['alg'])) ? $this->header['alg'] : 'HS256');

		if (isset($options['public_key']) && $options['public_key']) $this->public_key = $options['public_key'];
		if (isset($options['public_key']) && $options['private_key']) $this->private_key = $options['private_key'];
	}

	
	/**
	 * Create Pair Tokens
	 * 
	 * Create Access or Refresh Token
	 * 
	 * @param array $options
	 * @access public
	 * @return void
	 */
	public function createToken(array $options = []): string
	{
		if (isset($options['payload']) && sizeof($options['payload'])) $this->setPayload($options['payload']);
		if (isset($options['header']) && sizeof($options['header'])) $this->setHeader($options['header']);
		if (isset($options['secret']) && sizeof($options['secret'])) $this->setSecret($options['secret']);

		$exp = (!empty($this->payload['exp'])) ? $this->payload['exp'] : (time() + (60 * 30));
		
		if (!empty($this->payload['expiresIn'])) $exp = DateConvert::to_unixtime($this->payload['expiresIn']);
		
		unset($this->payload['expiresIn']);

		$this->payload['exp'] = $exp;
		$this->payload['iat'] = (!empty($this->payload['iat'])) ? $this->payload['iat'] : time();

		$base64_header = Base64::encode(json_encode($this->header));
		$base64_payload = Base64::encode(json_encode($this->payload));

		$token_unsigned = $base64_header.'.'.$base64_payload;
		$funcAlgo = constant('\Vudev\JsonWebToken\Algorithms::'.$this->hash_algo)[0];
		$signature = '';

		switch ($funcAlgo) {
			case 'hash_hmac':
				$signature = HashHmac::generateSignature($this->hash_algo, $token_unsigned, $this->secret);

				break;
			case 'openssl':
				$signature = OpenSSL::generateSignature($this->hash_algo, $token_unsigned, $this->private_key);

				break;
		}

		return $base64_header.'.'.$base64_payload.'.'.$signature;
	}

	/**
	 * Verify Tokens
	 * 
	 * @param string $token
	 * @access public
	 * @return boolean
	 */
	public function verifyToken($token): bool
	{
		list($header, $payload, $signature) = explode('.', $token);

		$base64Pyload = json_decode(Base64::decode($payload), true);
		$base64Header = json_decode(Base64::decode($header), true);

		if (empty($base64Pyload['exp'])) return false;
		if (empty($base64Pyload['iat'])) return false;

		$diffTimeExp = (int)$base64Pyload['exp'] - (int)$base64Pyload['iat'];
		$diffTimeCur = time() - (int)$base64Pyload['iat'];

		if ($diffTimeCur > $diffTimeExp) return false;

		$base64Data = $header.'.'.$payload;
		$signAlgo = strtoupper((!empty($base64Header['alg'])) ? $base64Header['alg'] : 'HS256');
		$funcAlgo = constant('Algorithms::'.$signAlgo)[0];

		switch ($funcAlgo) {
			case 'hash_hmac':
				$verifySign = HashHmac::verifySignature($signAlgo, $base64Data, $this->secret, $signature);

				break;
			case 'openssl':
				$verifySign = OpenSSL::verifySignature($signAlgo, $base64Data, $this->public_key, $signature);

				break;
		}

		return ($verifySign) ? true : false;
	}

	/**
	 * Update property payloads
	 * 
	 * @param array $payload
	 * @access protected
	 * @return void
	 */
	protected function setPayload(array $payload)
	{
		$searchs = [];

		foreach ($payload as $key => $val) {
			$searchs[$key] = false;

			foreach ($this->payload as $key2 => $val2) {
				if ($key === $key2) $searchs[$key] = true;
			}
		}

		foreach ($searchs as $key => $val) {
			$this->payload[$key] = $payload[$key];
		}
	}

	/**
	 * Update property headers
	 * 
	 * @param array $header
	 * @access protected
	 * @return void
	 */
	protected function setHeader(array $header)
	{
		$searchs = [];

		foreach ($header as $key => $val) {
			$searchs[$key] = false;

			foreach ($this->header as $key2 => $val2) {
				if ($key === $key2) $searchs[$key] = true;
			}
		}

		foreach ($searchs as $key => $val) {
			if ($val) {
				$this->header[$key] = $header[$key];
			} else {
				$this->header[] = $header[$key];
			}
		}

		$this->hash_algo = strtoupper((!empty($this->header['alg'])) ? $this->header['alg'] : 'HS256');
	}

	/**
	 * Update property secret
	 * 
	 * @param mixed $secret
	 * @access protected
	 * @return void
	 */
	protected function setSecret($secret)
	{
		$funcAlgo = constant('Algorithms::'.$this->hash_algo)[0];

		switch ($funcAlgo) {
			case 'hash_hmac':
				$this->secret = $secret;

				break;
			case 'openssl':
				$this->private_key = $secret;

				break;
		}
	}
}