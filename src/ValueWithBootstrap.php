<?php
/**
 * Yii 2 PHP file cache.
 *
 * @see       https://github.com/sergeymakinen/yii2-php-file-cache
 * @copyright Copyright (c) 2016 Sergey Makinen (https://makinen.ru)
 * @license   https://github.com/sergeymakinen/yii2-php-file-cache/blob/master/LICENSE The MIT License
 */

namespace sergeymakinen\caching;

use yii\base\Object;

/**
 * Allows caching a value along with a PHP code in [[PhpFileCache]].
 */
class ValueWithBootstrap extends Object
{
    /**
     * @var string the PHP code represented by this object.
     */
    public $bootstrap;

    /**
     * @var mixed the value represented by this object.
     */
    public $value;

    /**
     * Creates a new object.
     *
     * @param mixed $value the value represented by this object.
     * @param string $bootstrap the PHP code represented by this object.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($value, $bootstrap, $config = [])
    {
        $this->value = $value;
        $this->bootstrap = $bootstrap;
        parent::__construct($config);
    }
}
