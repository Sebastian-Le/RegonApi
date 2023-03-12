Return (JSON) basic company registration data for given NIP number from GUS API,
implementation uses test/sandbox enviroment for GUS API access.

Data from test environment is from 2019 and has additional masking elements for street names and some parts of company names.


```
git clone https://github.com/Sebastian-Le/RegonApi.git && cd RegonApi && composer install
```

```
vendor\bin\phpunit.bat --configuration phpunit.xml
```

index.php:
```php 
namespace RegonApi;

include_once('vendor/autoload.php');

$regon = new RegonApi();

$nip = $argv[1] ?? 5261040828;

print_r(json_decode($regon->getCompanyDetailsFromNIP((int)$nip)));
```

```
php index.php
```
You can pass NIP as argument to index.php.

Active => 5261040828
```php
php index.php 5261040828
```
Deleted => 5862113816
```php
php index.php 5862113816
```
No data => 5671799317
```php
php index.php 5671799317
```
