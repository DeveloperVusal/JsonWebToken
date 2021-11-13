# PHP Json Web Token
##### php >= 7.4

<br>

## Install
```
composer require vudev/jsonwebtoken
```

<br>

## Example
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