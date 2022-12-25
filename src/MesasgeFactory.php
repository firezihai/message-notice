<?php

declare(strict_types=1);

namespace Firezihai\MessageNotice;

use Firezihai\MessageNotice\Contracts\MessageInterface;

class MesasgeFactory
{
    public static function create($config)
    {
        $className = $config['drvier'] ?? '';
        if (empty($className)) {
            throw new \InvalidArgumentException('缺少驱动类');
        }

        if (! class_exists($className)) {
            throw new \InvalidArgumentException('Unsupported driver [' . $className . ']');
        }
        $classObj = new $className($config);

        if (! $classObj instanceof MessageInterface) {
            throw new \InvalidArgumentException('消息驱动类未实现MessageInterface接口');
        }
        return $classObj;
    }
}
