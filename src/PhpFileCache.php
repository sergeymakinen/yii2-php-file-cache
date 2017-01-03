<?php
/**
 * Yii 2 PHP file cache.
 *
 * @see       https://github.com/sergeymakinen/yii2-php-file-cache
 * @copyright Copyright (c) 2016-2017 Sergey Makinen (https://makinen.ru)
 * @license   https://github.com/sergeymakinen/yii2-php-file-cache/blob/master/LICENSE MIT License
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
                        if ($value[0]->bootstrap instanceof \Closure) {
                            $bootstrap = '';
                            $namespaces = $this->extractClosureNamespaces($value[0]->bootstrap);
                            if ($namespaces['namespace'] !== null) {
                                $bootstrap .= 'namespace ' . $namespaces['namespace'] . ";\n\n";
                            }
                            foreach ($namespaces['uses'] as $alias => $namespace) {
                                $bootstrap .= 'use ' . $namespace;
                                if (is_string($alias)) {
                                    $bootstrap .= ' as ' . $alias;
                                }
                                $bootstrap .= ";\n";
                            }
                            $bootstrap .= 'call_user_func(' . VarDumper::export($value[0]->bootstrap) . ');';
                        } else {
                            $bootstrap = $value[0]->bootstrap;
                        }
                        $bootstrap = trim($bootstrap) . "\n\n";
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

    /**
     * Returns Closure's namespace and uses.
     * @param \Closure $closure
     * @return array
     */
    private function extractClosureNamespaces(\Closure $closure)
    {
        $function = new \ReflectionFunction($closure);
        if ($function->getFileName() === false || strpos($function->getFileName(), 'eval()\'d code') !== false) {
            return [
                'namespace' => null,
                'uses' => [],
            ];
        }

        $closureNamespace = null;
        $closureUses = [];
        $source = implode(array_slice(
            file($function->getFileName()),
            0,
            $function->getEndLine()
        ));
        $tokens = token_get_all('<?php ' . $source);
        $state = null;
        $namespace = null;
        $alias = null;
        foreach ($tokens as $token) {
            if (is_array($token)) {
                if ($state === null && ($token[0] === T_NAMESPACE || $token[0] === T_USE)) {
                    $state = $token[0];
                    $namespace = '';
                } elseif ($state === T_USE && $alias === null && $token[0] === T_AS) {
                    $alias = '';
                } elseif ($state !== null && ($token[0] === T_STRING || $token[0] === T_NS_SEPARATOR)) {
                    if ($alias !== null) {
                        $alias .= $token[1];
                    } else {
                        $namespace .= $token[1];
                    }
                }
            } else {
                if ($state !== null && ($token === ';' || $token === '{')) {
                    if ($alias === null) {
                        if ($state === T_NAMESPACE) {
                            $closureNamespace = $namespace;
                            $closureUses = [];
                        } else {
                            $closureUses[] = $namespace;
                        }
                    } else {
                        $closureUses[$alias] = $namespace;
                        $alias = null;
                    }
                    $state = null;
                    $namespace = null;
                } elseif ($state === T_USE && $token === ',') {
                    if ($alias === null) {
                        $closureUses[] = $namespace;
                    } else {
                        $closureUses[$alias] = $namespace;
                        $alias = null;
                    }
                    $namespace = null;
                }
            }
        }
        return [
            'namespace' => $closureNamespace,
            'uses' => $closureUses,
        ];
    }
}
