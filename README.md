# DataCore Library

## Getting Started

Install using composer:

```bash
composer require terowoc/datacore
```

### Supported Databases

Below is a list of supported databases, and their compatibly tested versions alongside a list of supported features and relevant limits.

| Adapter | Status |
|---------|---------|
| MySQL | ✅ |
| Postgres | ✅ |
| SQLlite | ✅ |


## Usage

### Connecting to a Database 

```php
require_once __DIR__ . '/vendor/autoload.php';

use Terowoc\DataCore\DataBase;

$database = 'datacore'; // Database Name
$username = 'postgres'; // Database UserName
$password = ''; // Database Password
$host = 'localhost'; // Database host [default: 127.0.0.1]
$port = 3306; // Database port [default: 5432]
$driver = 'pgsql'; // Driver name [default: pgsql]

$db = new DataBase(
	$database, 
	$username,
	$password,
	$host,
	$port,
	$driver
);

```
