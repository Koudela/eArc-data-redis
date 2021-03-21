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

interface ParameterInterface
{
    const HASH_KEY_PREFIX = 'earc.data_redis.hash_key_prefix'; // default 'earc-data'
}
