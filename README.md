OK API
=====================
[![Latest Stable Version](https://poser.pugx.org/nullref/ok-api/v/stable)](https://packagist.org/packages/nullref/ok-api) [![Total Downloads](https://poser.pugx.org/nullref/ok-api/downloads)](https://packagist.org/packages/nullref/ok-api) [![Latest Unstable Version](https://poser.pugx.org/nullref/ok-api/v/unstable)](https://packagist.org/packages/nullref/ok-api) [![License](https://poser.pugx.org/nullref/ok-api/license)](https://packagist.org/packages/nullref/ok-api)

(WIP)

php API for [ok.ru](https://ok.ru/)

## Installation
```bash
composer require nullref/ok-api
```


## Usage
```php
$auth['client_id'] = '0000000000';
$auth['client_secret'] = 'AAAAAAAAAAAAAAAAAAAAA';
$auth['application_key'] = 'AAAAAAAAAAAAAA';
$auth['access_token'] = 'AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAA';


$okApi = new OkApi();

$okApi->auth = $auth;
$okApi->log = true;

$method = 'photosV2.getUploadUrl';

$result = $okApi->makeRequest($method, [
    'gid' => '51270023053448',
    'count' => 3,
]);

print_r($result);


```