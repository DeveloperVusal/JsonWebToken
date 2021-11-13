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
$jwt = new JWT([
    'iss' => 'JWT PHP',
    'sub' => 'Auth',
    'expiresIn' => '15min',
    'user_id'=> '1'
]);
$access_token = $jwt->createToken();

// eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJ1c2VyX2lkIjoxLCJleHAiOjE2MzY4MzY4MjksImlhdCI6MTYzNjgzNjgxNH0.7fbfd55b16f88ac056d1224a3cd1989085f59abdc1f8ba62c58c7dddfb4b46a1
```