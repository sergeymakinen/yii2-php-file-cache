<?php

namespace sergeymakinen\yii\phpfilecache\tests;

use sergeymakinen\yii\phpfilecache\Cache;
use yii\helpers\ArrayHelper;

abstract class TestCase extends \sergeymakinen\yii\tests\TestCase
{
    protected function createCache(array $config = [])
    {
        $config = ArrayHelper::merge([
            'cachePath' => '@tests/runtime/cache',
        ], $config);
        return new Cache($config);
    }
}
