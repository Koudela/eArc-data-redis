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

use eArc\Data\Initializer;
use eArc\Data\ParameterInterface;
use eArc\DataRedis\RedisDataBridge;
use PHPUnit\Framework\TestCase;

class IntegrationTest extends TestCase
{
    public function init(): void
    {
        Initializer::init();

        di_tag(ParameterInterface::TAG_ON_LOAD, RedisDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_LOAD, MyDatabaseBridge::class);

        di_tag(ParameterInterface::TAG_ON_PERSIST, RedisDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_PERSIST, MyDatabaseBridge::class);

        di_tag(ParameterInterface::TAG_ON_REMOVE, RedisDataBridge::class);
        di_tag(ParameterInterface::TAG_ON_REMOVE, MyDatabaseBridge::class);
    }

    public function testBridge(): void
    {
        $this->init();

        $entity = new MyEntity('yet-another-primary-key');
        data_persist($entity);

        self::assertSame($entity, data_load(MyEntity::class, 'yet-another-primary-key'));
        data_detach($entity::class);
        self::assertNotSame($entity, data_load(MyEntity::class, 'yet-another-primary-key'));
        self::assertEquals($entity, data_load(MyEntity::class, 'yet-another-primary-key'));
    }
}
