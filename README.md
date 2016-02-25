### potato-orm

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-scrutinizer-build]][link-scrutinizer-build]
[![Coverage Status][ico-coveralls]][link-coveralls]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

A light-weight ORM which allows you to insert records into a
database, retrieve them, update them, and delete them.

### Install

Via Composer

``` bash
$ composer require andela-aonyango/potato-orm
```
In the package directory, run
``` bash
$ composer install
```

### Usage
Edit the example .env provided in the root of this package with the database settings you will use (make sure you .gitignore **YOUR** .env file). The one given uses MYSQL settings. PDO supports many [database drivers](https://secure.php.net/manual/en/pdo.drivers.php) so you can get familiar with what are valid/invalid inputs for the DSN and so on.

#### Sample Table
```sql
create table Person (
    id int unsigned auto_increment primary key,
    first_name varchar(30) not null,
    last_name varchar(30) not null,
    age int(2),
    gender varchar(7)
);
```
#### Sample Usage
``` php
<?php
// Example usage of this ORM

require "vendor/autoload.php";

use PotatoORM\Models\Person;

$person = new Person();
$person->first_name = "yua";
$person->last_name = "madha";
$person->age = 73;

// the add() method inserts an object and returns the last inserted id
$id = $person->add();

// retrieve the just-added person
$test = $person->find($id);

print_r($test);

$test->gender = "female";
$test->update();

// is the update successful
print_r($test->find($id));

// delete the person from the database
$test->remove();

// retrieve all people
print_r($person->findAll());
?>
```

### Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

### Testing

``` bash
$ composer test
```

### Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

### Security

If you discover any security related issues, please email andrew.onyango@andela.com instead of using the issue tracker.

### Credits

- [andela-aonyango][link-author]
- [All Contributors][link-contributors]

### License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/andela-aonyango/potato-orm.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-scrutinizer-build]: https://scrutinizer-ci.com/g/andela-aonyango/potato-orm/badges/build.png?b=master
[ico-coveralls]: https://coveralls.io/repos/github/andela-aonyango/potato-orm/badge.svg?branch=master
[ico-code-quality]: https://img.shields.io/scrutinizer/g/andela-aonyango/potato-orm.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/andela-aonyango/potato-orm.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/andela-aonyango/potato-orm
[link-scrutinizer-build]: https://scrutinizer-ci.com/g/andela-aonyango/potato-orm/build-status/master
[link-coveralls]: https://coveralls.io/github/andela-aonyango/potato-orm?branch=master
[link-code-quality]: https://scrutinizer-ci.com/g/andela-aonyango/potato-orm
[link-downloads]: https://packagist.org/packages/andela-aonyango/potato-orm
[link-author]: https://github.com/andela-aonyango
[link-contributors]: ../../contributors
