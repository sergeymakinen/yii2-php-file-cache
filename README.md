# Yii 2 PHP file cache

Yii 2 cache component that uses native PHP files to store cache data so:

- it's possible to improve a PHP performance by storing a precompiled data bytecode in a shared memory (objects will be serialized though)
- allows to include an arbitrary PHP code to bootstrap something
- it's fully compatible with the standard [Yii 2 cache interface](http://www.yiiframework.com/doc-2.0/yii-caching-cache.html)

[![Code Quality](https://img.shields.io/scrutinizer/g/sergeymakinen/yii2-php-file-cache.svg?style=flat-square)](https://scrutinizer-ci.com/g/sergeymakinen/yii2-php-file-cache) [![Build Status](https://img.shields.io/travis/sergeymakinen/yii2-php-file-cache.svg?style=flat-square)](https://travis-ci.org/sergeymakinen/yii2-php-file-cache) [![Code Coverage](https://img.shields.io/codecov/c/github/sergeymakinen/yii2-php-file-cache.svg?style=flat-square)](https://codecov.io/gh/sergeymakinen/yii2-php-file-cache) [![SensioLabsInsight](https://img.shields.io/sensiolabs/i/cb947efa-90f3-4054-a348-20d67327e8a3.svg?style=flat-square)](https://insight.sensiolabs.com/projects/cb947efa-90f3-4054-a348-20d67327e8a3)

[![Packagist Version](https://img.shields.io/packagist/v/sergeymakinen/yii2-php-file-cache.svg?style=flat-square)](https://packagist.org/packages/sergeymakinen/yii2-php-file-cache) [![Total Downloads](https://img.shields.io/packagist/dt/sergeymakinen/yii2-php-file-cache.svg?style=flat-square)](https://packagist.org/packages/sergeymakinen/yii2-php-file-cache) [![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE)

## Installation

The preferred way to install this extension is through [composer](https://getcomposer.org/download/).

Either run

```bash
composer require "sergeymakinen/yii2-php-file-cache:^1.0"
```

or add

```json
"sergeymakinen/yii2-php-file-cache": "^1.0"
```

to the require section of your `composer.json` file.

## Usage

Set the following Yii 2 configuration parameters:

```php
[
    'components' => [
        'phpCache' => [
            'class' => 'sergeymakinen\caching\PhpFileCache',
        ],
    ],
]
```

And you can use it like any Yii 2 cache class:

```php
Yii::$app->phpCache->set('foo', 'bar')
```

### Caching values with a PHP code

If you need to execute any PHP bootstrap code before you get a value from a cache, pass a `sergeymakinen\caching\ValueWithBootstrap` instance with the value and a PHP code (which can be multiline of course) as a string to `set()`:

```php
Yii::$app->phpCache->set(
    'foo',
    new sergeymakinen\caching\ValueWithBootstrap(
        'bar',
        'Yii::$app->params[\'fromCache\'] = true;'
    )
);
```
