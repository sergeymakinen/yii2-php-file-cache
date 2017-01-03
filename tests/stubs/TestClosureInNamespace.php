<?php

namespace sergeymakinen\tests\caching\stubs\none {

    use sergeymakinen\tests\caching\PhpFileCacheTest as Alias;

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

namespace sergeymakinen\tests\caching\stubs {

    use sergeymakinen\tests\caching\PhpFileCacheTest as Alias,
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
