<?php

namespace sergeymakinen\tests\caching;

use sergeymakinen\caching\PhpFileCache;
use yii\helpers\ArrayHelper;

abstract class TestCase extends \sergeymakinen\tests\TestCase
{
    protected function createCache(array $config = [])
    {
        $config = ArrayHelper::merge([
            'cachePath' => '@tests/runtime/cache',
        ], $config);
        return new PhpFileCache($config);
    }
}
