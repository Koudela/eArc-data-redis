<?php declare(strict_types=1);
/**
 * e-Arc Framework - the explicit Architecture Framework
 *
 * @package earc/data-redis
 * @link https://github.com/Koudela/eArc-data-redis/
 * @copyright Copyright (c) 2019-2021 Thomas Koudela
 * @license http://opensource.org/licenses/MIT MIT License
 */

namespace eArc\DataRedis;

use eArc\Data\Entity\Interfaces\EntityInterface;
use eArc\Data\Manager\Interfaces\Events\OnLoadInterface;
use eArc\Data\Manager\Interfaces\Events\OnPersistInterface;
use eArc\Data\Manager\Interfaces\Events\OnRemoveInterface;
use Redis;

class RedisDataBridge implements OnPersistInterface, OnLoadInterface, OnRemoveInterface
{
    protected Redis $redis;
    protected string $hashKeyPrefix;

    public function __construct()
    {
        $this->redis = new Redis();
        $this->hashKeyPrefix = di_param(ParameterInterface::HASH_KEY_PREFIX, 'earc-data');
    }

    public function onPersist(array $entities): void
    {
        $args = [];
        foreach ($entities as $entity) {
            $args[$entity::class][] = $entity->getPrimaryKey();
            $args[$entity::class][] = serialize($entity);
        }

        foreach ($args as $fQCN => $values) {
            $this->redis->hSet($this->hashKeyPrefix.'::'.$fQCN, ...$values);
        }
    }

    public function onLoad(string $fQCN, array $primaryKeys, array &$postLoadCallables): array
    {
        $result = $this->redis->hMGet($this->hashKeyPrefix.'::'.$fQCN, $primaryKeys);

        $entities = [];

        foreach ($result as $primaryKey => $item) {
            if (!is_null($item)) {
                $object = unserialize($item);
                if ($object instanceof EntityInterface) {
                    $entities[$primaryKey] = $object;
                }
            }
        }

        if (count($entities) < count($result)) {
            $postLoadCallables[] = function (array $callbackEntities) use ($entities) {
                $newEntities = [];
                /** @var EntityInterface $callbackEntity */
                foreach ($callbackEntities as $callbackEntity) {
                    if (!array_key_exists($callbackEntity->getPrimaryKey(), $entities)) {
                        $newEntities[$callbackEntity->getPrimaryKey()] = $callbackEntity;
                    }
                }
                $this->onPersist($newEntities);
            };
        }

        return $entities;
    }

    public function onRemove(string $fQCN, array $primaryKeys): void
    {
        $this->redis->hDel($this->hashKeyPrefix.'::'.$fQCN, ...$primaryKeys);
    }

}
