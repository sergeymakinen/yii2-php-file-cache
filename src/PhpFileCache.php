<?php
/**
 * Yii 2 PHP file cache.
 *
 * @see       https://github.com/sergeymakinen/yii2-php-file-cache
 * @copyright Copyright (c) 2016 Sergey Makinen (https://makinen.ru)
 * @license   https://github.com/sergeymakinen/yii2-php-file-cache/blob/master/LICENSE The MIT License
 */

namespace sergeymakinen\caching;

use yii\caching\FileCache;
use yii\helpers\VarDumper;

/**
 * PhpFileCache implements a cache component using PHP files.
 */
class PhpFileCache extends FileCache
{
    /**
     * @inheritDoc
     */
    public $cacheFileSuffix = '.php';

    /**
     * @inheritDoc
     */
    public function init()
    {
        parent::init();
        if ($this->serializer === null) {
            $this->serializer = [
                function ($value) {
                    if ($value[0] instanceof ValueWithBootstrap) {
                        $bootstrap = trim($value[0]->bootstrap) . "\n\n";
                        $value[0] = $value[0]->value;
                    } else {
                        $bootstrap = '';
                    }
                    return "<?php\n\n{$bootstrap}return " . VarDumper::export($value) . ";\n";
                },
                function ($value) {
                    return $value;
                },
            ];
        }
    }

    /**
     * @inheritDoc
     */
    protected function getValue($key)
    {
        $cacheFile = $this->getCacheFile($key);
        if (@filemtime($cacheFile) > time()) {
            /** @noinspection PhpIncludeInspection */
            $cacheValue = @include $cacheFile;
            if (is_array($cacheValue) && array_key_exists(0, $cacheValue)) {
                return $cacheValue;
            }
        }

        return false;
    }
}
