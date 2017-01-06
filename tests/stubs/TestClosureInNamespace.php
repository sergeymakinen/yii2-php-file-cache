<?php

namespace sergeymakinen\yii\phpfilecache\tests\stubs\none {

    use sergeymakinen\yii\phpfilecache\tests\CacheTest as Alias;

    class TestClosureInNamespace
    {
        public static function getClosure()
        {
            $closure = function () {
                Alias::$external = 'foobaz';
            };
            return $closure;
        }
    }
}

namespace sergeymakinen\yii\phpfilecache\tests\stubs {

    use sergeymakinen\yii\phpfilecache\tests\CacheTest as Alias,
        yii\helpers\StringHelper;

    use yii\helpers\ArrayHelper,
        yii\helpers\VarDumper;

    use yii\helpers\Html as Alias2;

    class TestClosureInNamespace
    {
        public static function getClosure()
        {
            $closure = function () {
                Alias::$external = 'foobar';
            };
            return $closure;
        }
    }
}
