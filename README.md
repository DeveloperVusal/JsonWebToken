# PHP Json Web Token
##### php >= 7.4

<br>

## Install
```
composer require vudev/jsonwebtoken
```

<br>

## Description
### Syntax
```php
$jwt = new JWT([
    'payload' => [
        'user_id' => 1,
        'expiresIn' => '15min'
    ],
    'secret' => 'NWPnXk>l^{TVhaU'
]);
```

<br>

### Object options
| Name        | Type | Default | Required | Description |
| ----------- | ---- | ------- | -------- | ----------- |
| payload     | array | [] | required  | Useful data that is stored inside the JWT. This data is also called JWT-claims (applications) |
| secret      | string | '' | required  | Secret key. This option is required when using `hash_hmac` |
| header      | array | ['alg' => 'HS256', 'typ'=> 'JWT'] | optional  | Сontains information about how the JWT signature should be calculated |
| private_key | string - a PEM formatted key | '' | required  | OpenSSLAsymmetricKey -  a key, returned by openssl_get_privatekey(). This option is required when using `openssl`   |

<br>

### Algorithms
| Method      | Algorithm | Constant |
| ----------- | --------- | -------- |
| hash_hmac   | SHA256    | HS256    |
| hash_hmac   | SHA384    | HS384    |
| hash_hmac   | SHA512    | HS512    |
| openssl     | SHA256    | RS256    |
| openssl     | SHA384    | RS384    |
| openssl     | SHA512    | RS512    |

<br>

### Methods
| Name        | Arguments | Description                |
| ----------- | ------- | ---------------------------- |
| createToken | $options — as options of the JWT object | Creating access or refresh tokens |
| verifyToken | $token — access token<br>$key — secret or public key | Checking tokens for validity      |
| json | $token — access token | Getting payload and header data      |

<br>

## Creating a pair tokens with hash_hmac
```php
$secret_key = 'one unique secret key';

$jwt = new JWT([
    'payload' => [
        'user_id' => 1,
        'expiresIn' => '15min'
    ],
    'secret' => $secret_key
]);

$access_token = $jwt->createToken();
$refresh_token = $jwt->createToken([
    'payload' => [
        'user_id' => 1,
        'expiresIn' => '30d'
    ]
]);


# Results

/*
access_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MzY4NDQ0ODQsImlhdCI6MTYzNjg0MzU4NH0.63f42f8dfa08a65435e033b5ce0ba59e2828efe2c086c4694d31cd31ca30d1b3

refresh_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2Mzk0MzU1ODQsImlhdCI6MTYzNjg0MzU4NH0.87ba6ff9dcbe1c649a9ee0f24fc25ebeb919ccf3688139c2200a0e243b75e9cb
*/
```

<br>

## Creating a pair tokens with openssl
```php
$key_pair = openssl_pkey_new([
    'private_key_bits' => 2048,
    'private_key_type' => OPENSSL_KEYTYPE_RSA
]);

openssl_pkey_export($key_pair, $private_key_pem);

$details = openssl_pkey_get_details($key_pair);
$public_key_pem = $details['key'];

$jwt = new JWT([
    'header' => [
        'alg' => 'RS256',
        'typ' => 'JWT'
    ],
    'payload' => [
        'user_id' => 1,
        'expiresIn' => '15min'
    ],
    'private_key' => $private_key_pem
]);

$access_token = $jwt->createToken();
$refresh_token = $jwt->createToken([
    'payload' => [
        'user_id' => 1,
        'expiresIn' => '30d'
    ]
]);


# Results

/*
access_token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2NTE5NjU5ODcsImlhdCI6MTY1MTk2NTA4N30.7b10d7aa75e49bb5f8e065e0aa06e7438b6539cb00629c0aa5ef53a213c1755f456aef546ea83e5bd6dbc1c31aab8fa2036268491772b9fbfdbdfffdac0648a09af6d488c7a307e28e0850746f184b57a5d15ccb2477996ac548ca72230cdd775ab0fc36f20250b89ff6b1e7e3ec3e215ac3483a7be539596109838bdb0b28f49b209d323da3d32c2adf2c11a902f4c82545d98fda30bebb99f8f1e3970e8fff87a831e2d5eb355f37ac9edb7cff130eef3386943d2a35250ecf7b3e017b1a370b17cff69356485fd9f489dd426bdf8530b3fefdb54ebca6990a605a90d41be2e2c5334af104010e87b43802f19979333f308b9910ee0708d9139133ee15a44a

refresh_token: eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2NTQ1NTcwODcsImlhdCI6MTY1MTk2NTA4N30.b3f6328ec2a0da05c388735dfcfa882829cdb7a5ffd8526ff7897a117ef24ce22342af58dc67d2144e62c7516aca10930484051f67b7ff4c25429f914b78aa4eb6d908e6443b524c6efa9bd8c75d4f411e1dbb0ea37e673f5ae043c175547df2adc9482ced24c2d46545028231d6d234f2806bb7ad3a969430ce73a0f8a2ef3483368835bb516d9a9ab891f49cbbdc7b02fa0944aabb98d0eae7e9476730952c3d4c569da313c8612b8bad681a40a97e8872a3a788005b75337497c08643d9d5a928783f560fac9b3497e67605f9409f118eab6322daf6ea6974669dbc8f8c63437a7fda1a86cdf47933b539f0fc133a557caf64c756bb1a25fd939b648c752d

*/
```

<br>

## Verifying a token with hash_hmac
```php
$token = file_get_contents('./token.txt');
$secret_key = 'one unique secret key';

$jwt = new JWT;

if ($jwt->verifyToken($token, $secret_key)) {
    print_r('Valid token!');
} else {
    print_r('Invalid token!');
}
```

<br>

## Verifying a token with openssl
```php
$token = file_get_contents('./token.txt');
$publicKey = file_get_contents('./public_key.pem');

$jwt = new JWT;

if ($jwt->verifyToken($token, $publicKey)) {
    print_r('Valid token!');
} else {
    print_r('Invalid token!');
}
```

## Getting token data
```php
$token = file_get_contents('./token.txt');

$jwt = new JWT;
$tokenData = $jwt->json($token);

/*

Array
(
    [header] => Array
        (
            [alg] => RS256
            [typ] => JWT
        )

    [payload] => Array
        (
            [user_id] => 1
            [exp] => 1651966671
            [iat] => 1651965771
        )
)

*/
```