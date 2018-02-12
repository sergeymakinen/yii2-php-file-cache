<?php
/**
 * Yii 2 PHP file cache
 *
 * @see       https://github.com/sergeymakinen/yii2-php-file-cache
 * @copyright Copyright (c) 2016-2018 Sergey Makinen (https://makinen.ru)
 * @license   https://github.com/sergeymakinen/yii2-php-file-cache/blob/master/LICENSE MIT License
 */

namespace sergeymakinen\yii\phpfilecache;

use yii\base\BaseObject;

/**
 * Allows caching a value along with a PHP code in [[Cache]].
 */
class ValueWithBootstrap extends BaseObject
{
    /**
     * @var string|\Closure the PHP code represented by this object.
     * Since 1.1 it can also be a Closure serializable by VarDumper.
     */
    public $bootstrap;

    /**
     * @var mixed the value represented by this object.
     */
    public $value;

    /**
     * Creates a new object.
     * @param mixed $value the value represented by this object.
     * @param string|\Closure $bootstrap the PHP code represented by this object.
     * Since 1.1 it can also be a Closure serializable by VarDumper.
     * @param array $config name-value pairs that will be used to initialize the object properties.
     */
    public function __construct($value, $bootstrap, $config = [])
    {
        $this->value = $value;
        $this->bootstrap = $bootstrap;
        parent::__construct($config);
    }
}
