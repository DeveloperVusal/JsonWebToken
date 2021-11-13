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
```
access_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MzY4NDMxOTMsImlhdCI6MTYzNjg0MzE3OH0.b97902961abb8287dc952abe4e69e04ca91795e445bbeec9954344daf09f325d

refresh_token: eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2Mzk0MzUxNzgsImlhdCI6MTYzNjg0MzE3OH0.9ae083d10593249270aab2eac84f1c848cc042aef9dc679d2d7130c436942907
```