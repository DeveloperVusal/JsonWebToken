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
| secret      | string | '' | required  | Secret key for `hash_hmac` |
| header      | array | ['alg' => 'HS256', 'typ'=> 'JWT'] | optional  | Сontains information about how the JWT signature should be calculated |
| public_key  | string - a PEM formatted key | '' | optional  | OpenSSLAsymmetricKey - a key, returned by openssl_get_publickey() |
| private_key | string - a PEM formatted key | '' | optional  | OpenSSLAsymmetricKey -  a key, returned by openssl_get_privatekey() |

<br>

### Methods
| Name        | Arguments | Description                |
| ----------- | ------- | ---------------------------- |
| createToken | $options — as options of the JWT object | Creating access or refresh tokens |
| verifyToken | $token — data type string | Checking tokens for validity      |

<br>

## Example with hash_hmac
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
```

<br>

## Results
```
access_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MzY4NDQ0ODQsImlhdCI6MTYzNjg0MzU4NH0.63f42f8dfa08a65435e033b5ce0ba59e2828efe2c086c4694d31cd31ca30d1b3

refresh_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2Mzk0MzU1ODQsImlhdCI6MTYzNjg0MzU4NH0.87ba6ff9dcbe1c649a9ee0f24fc25ebeb919ccf3688139c2200a0e243b75e9cb
```