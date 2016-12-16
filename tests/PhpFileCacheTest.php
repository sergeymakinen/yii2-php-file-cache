<?php

namespace sergeymakinen\tests\caching;

use sergeymakinen\caching\ValueWithBootstrap;
use sergeymakinen\tests\caching\stubs\TestModel;
use yii\caching\ExpressionDependency;
use yii\helpers\FileHelper;

class PhpFileCacheTest extends TestCase
{
    public static $external;

    public function valuesProvider()
    {
        return [
            ['foo'],
            [123],
            [123.45],
            [null],
            [true],
        ];
    }

    /**
     * @dataProvider valuesProvider
     *
     * @param mixed $value
     */
    public function testSerialize($value)
    {
        $cache = $this->createCache();
        $this->assertTrue($cache->flush());
        $cache->set('foo', $value);
        $this->assertEquals($value, $cache->get('foo'));
    }

    public function testSerializeModel()
    {
        $cache = $this->createCache();
        $this->assertTrue($cache->flush());
        $model = new TestModel();
        $model->attributes = [
            'foo' => 123,
            'bar' => true,
        ];
        $this->assertTrue($model->validate());
        $cache->set('foo', $model);
        $this->assertEquals($model, $cache->get('foo'));
    }

    /**
     * @dataProvider valuesProvider
     *
     * @param mixed $value
     */
    public function testSerializeWithDependency($value)
    {
        $cache = $this->createCache();
        $this->assertTrue($cache->flush());
        $cache->set('foo', $value, 3600, new ExpressionDependency(['expression' => 'time() + 3600 > microtime()']));
        $this->assertEquals($value, $cache->get('foo'));
    }

    /**
     * @dataProvider valuesProvider
     *
     * @param mixed $value
     */
    public function testSerializeWithBoostrap($value)
    {
        $cache = $this->createCache();
        $this->assertTrue($cache->flush());
        self::$external = null;
        $cache->set('foo', new ValueWithBootstrap($value, get_class($this) . '::$external = \'foobar\';'));
        $this->assertNull(self::$external);
        $this->assertEquals($value, $cache->get('foo'));
        $this->assertEquals('foobar', self::$external);
    }

    public function testNotExisting()
    {
        $this->assertFalse($this->createCache()->get('bar'));
    }

    public function testEmptyCacheFile()
    {
        $cache = $this->createCache();
        $this->assertTrue($cache->flush());
        $cacheFile = $this->invokeInaccessibleMethod($cache, 'getCacheFile', ['foo']);
        FileHelper::createDirectory(dirname($cacheFile));

        file_put_contents($cacheFile, '<?php ');
        touch($cacheFile, time() + 3600);
        $this->assertFalse($cache->get('foo'));

        file_put_contents($cacheFile, '<?php return [];');
        touch($cacheFile, time() + 3600);
        $this->assertFalse($cache->get('foo'));
    }
}
