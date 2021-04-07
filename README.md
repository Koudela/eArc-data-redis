# eArc-data-redis

Redis bridge providing entity-caching for the [earc/data](https://github.com/Koudela/eArc-data)
abstraction.

## installation

Install the earc/data-redis library via composer.

```bash
$ composer require earc/data-redis
```

## basic usage

Initialize the earc/data abstraction in your index.php, bootstrap or configuration 
script.

```php
use eArc\Data\Initializer;

Initializer::init();
```

Then register the earc/data-redis bridge to the earc/data `onLoad`, `onPersit` 
and `onRemove` events.

```php
use eArc\Data\ParameterInterface;
use eArc\DataRedis\RedisDataBridge;

// The order of the on load tagging is important!
di_tag(ParameterInterface::TAG_ON_LOAD, RedisDataBridge::class);
di_tag(ParameterInterface::TAG_ON_LOAD, MyDatabaseBridge::class);

di_tag(ParameterInterface::TAG_ON_PERSIST, RedisDataBridge::class);
di_tag(ParameterInterface::TAG_ON_PERSIST, MyDatabaseBridge::class);

di_tag(ParameterInterface::TAG_ON_REMOVE, RedisDataBridge::class);
di_tag(ParameterInterface::TAG_ON_REMOVE, MyDatabaseBridge::class);
```

*Important*: For the `onLoad` event you have to tag the `RedisDataBridge` before 
your database bridge. Otherwise, the entities will never be loaded from cache.

Now earc/data uses your redis server to cache your entities.

### connection parameter for redis

By default, earc/data-redis uses `localhost` and the defaults of the php-redis-extension.
You can overwrite these defaults:

```php
use eArc\DataRedis\ParameterInterface;

di_set_param(ParameterInterface::REDIS_CONNECTION, ['127.0.0.1', 6379]);
```

This array is handed to the `Redis::connect()` method as arguments. Consult the 
[phpredis documentation](https://github.com/phpredis/phpredis/#connect-open) for 
valid values and configuration options.

## advanced usage

earc/data-redis uses [redis hashes](https://redis.io/commands#hash) to cache your entities.
By default, the hash-keys are prefixed by `earc-data`. If you need another prefix
to manage the redis namespace, you can overwrite the default:

```php
use eArc\DataRedis\ParameterInterface;

di_set_param(ParameterInterface::HASH_KEY_PREFIX, 'some-hash-key-prefix');
```

## releases

### release 0.0

* the first official release
* PHP ^8.0
* Redis >=4.0.0
