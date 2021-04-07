<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data-redis
 * @link https://github.com/Koudela/eArc-data-redis/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataRedisTests;

use eArc\Data\Manager\Interfaces\Events\OnLoadInterface;
use eArc\Data\Manager\Interfaces\Events\OnPersistInterface;
use eArc\Data\Manager\Interfaces\Events\OnRemoveInterface;
use Redis;

class MyDatabaseBridge implements OnPersistInterface, OnLoadInterface, OnRemoveInterface
{
    public function onPersist(array $entities): void
    {
    }

    public function onLoad(string $fQCN, array $primaryKeys, array &$postLoadCallables): array
    {
        return [];
    }

    public function onRemove(string $fQCN, array $primaryKeys): void
    {
    }

}
