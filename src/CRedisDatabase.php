<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   Credis-bridge
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\CRedis;

use Vainyl\CRedis\Exception\NotImplementedRedisException;
use Vainyl\Database\CursorInterface;
use Vainyl\Redis\AbstractRedis;
use Vainyl\Redis\Multi\MultiRedisInterface;
use Vainyl\Redis\Multi\Pipeline\PipelineRedis;
use Vainyl\Redis\Multi\Transaction\TransactionRedis;
use Vainyl\Redis\RedisInterface;

/**
 * Class CRedisDatabase
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 *
 * @method \Redis getConnection
 */
class CRedisDatabase extends AbstractRedis implements RedisInterface
{
    private $multi = false;

    /**
     * @inheritDoc
     */
    public function append(string $key, string $value): bool
    {
        $result = $this->getConnection()->append($key, $value);

        return $this->multi ? false : (0 < $result);
    }

    /**
     * @param string $key
     * @param int    $timeout
     *
     * @return mixed
     */
    public function bLPop(string $key, int $timeout = 0)
    {
        $result = $this->getConnection()->blPop([$key], $timeout);

        return $this->multi ? '' : $result;
    }

    /**
     * @param string $key
     * @param int    $timeout
     *
     * @return mixed
     */
    public function bRPop(string $key, int $timeout = 0)
    {
        $result = $this->getConnection()->brPop([$key], $timeout);

        return $this->multi ? '' : $result;
    }

    /**
     * @param string $source
     * @param string $destination
     * @param int    $timeout
     *
     * @return mixed
     */
    public function bRPopLPush(string $source, string $destination, int $timeout = 0)
    {
        $result = $this->getConnection()->brpoplpush($source, $destination, $timeout);

        return $this->multi ? '' : $result;
    }

    /**
     * @return bool
     */
    public function bgRewriteAof(): bool
    {
        $result = $this->getConnection()->bgrewriteaof();

        return $this->multi ? false : $result;
    }

    /**
     * @return bool
     */
    public function bgSave(): bool
    {
        $result = $this->getConnection()->bgsave();

        return $this->multi ? false : $result;
    }

    /**
     * @param string $key
     * @param int    $start
     * @param int    $end
     *
     * @return int
     */
    public function bitCount(string $key, int $start = 0, int $end = -1): int
    {
        $result = $this->getConnection()->bitCount($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @param string $key
     * @param array  $commands
     *
     * @return bool
     */
    public function bitField(string $key, array $commands): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @param string $key
     * @param string $operation
     * @param string $destination
     * @param array  $sources
     *
     * @return int
     */
    public function bitOp(string $key, string $operation, string $destination, array $sources): int
    {
        $result = $this->getConnection()->bitOp($operation, $key, $sources);

        return $this->multi ? 0 : $result;
    }

    /**
     * @param string $key
     * @param int    $bit
     * @param int    $start
     * @param int    $end
     *
     * @return int
     */
    public function bitPos(string $key, int $bit, int $start = 0, int $end = -1): int
    {
        $result = $this->getConnection()->bitpos($key, $bit, $start, ($end === -1) ? null : $end);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function decr(string $key): int
    {
        $result = $this->getConnection()->decr($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function decrBy(string $key, int $value): int
    {
        $result = $this->getConnection()->decrBy($key, $value);

        return $this->multi ? 0 : $result;
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function delete($key)
    {
        $result = $this->getConnection()->del($key);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @param iterable $keys
     *
     * @return mixed
     */
    public function deleteMultiple($keys)
    {
        $this->getConnection()->del(...$keys);

        return false;
    }

    /**
     * @inheritDoc
     */
    public function exec(MultiRedisInterface $multiRedis): array
    {
        $this->multi = false;

        return $this->getConnection()->exec();
    }

    /**
     * @param string $key
     *
     * @return bool
     */
    public function exists(string $key): bool
    {
        $result = $this->getConnection()->exists($key);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function expire(string $key, int $ttl): bool
    {
        $result = $this->getConnection()->expire($key, $ttl);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function expireAt(string $key, int $ttl): bool
    {
        $result = $this->getConnection()->expireAt($key, $ttl);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function flush(): RedisInterface
    {
        $this->getConnection()->flushDB();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function flushAll(): RedisInterface
    {
        $this->getConnection()->flushAll();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function geoAdd(string $key, float $longitude, float $latitude, $value): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function geoDist(string $key, $from, $to): float
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function geoHash(string $key, $value): string
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function geoPos(string $key, $value): array
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function geoRadius(
        string $key,
        float $longitude,
        float $latitude,
        float $radius,
        string $uom,
        array $options = []
    ): array {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function geoRadiusByMember(string $key, $member, float $radius, string $uom, array $options = []): array
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function get($key, $default = null)
    {
        if (false === ($result = $this->getConnection()->get($key))) {
            return $default;
        }

        return $result;
    }

    /**
     * @inheritDoc
     */
    public function getBit(string $key, int $offset): int
    {
        $result = $this->getConnection()->getBit($key, $offset);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function getRange(string $key, int $from, int $to): string
    {
        $result = $this->getConnection()->getRange($key, $from, $to);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function getSet(string $key, $value)
    {
        $result = $this->getConnection()->getSet($key, $value);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function hDel(string $key, string $field): bool
    {
        $result = $this->getConnection()->hDel($key, $field);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function hExists(string $key, string $field): bool
    {
        $result = $this->getConnection()->hExists($key, $field);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function hGet(string $key, string $field)
    {
        $result = $this->getConnection()->hGet($key, $field);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function hGetAll(string $key): array
    {
        $result = $this->getConnection()->hGetAll($key);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function hIncrBy(string $key, string $field, int $value): int
    {
        $result = $this->getConnection()->hIncrBy($key, $field, $value);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function hIncrByFloat(string $key, string $field, float $floatValue): float
    {
        $result = $this->getConnection()->hIncrByFloat($key, $field, $floatValue);

        return $this->multi ? 0.0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function hKeys(string $key): array
    {
        $result = $this->getConnection()->hKeys($key);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function hLen(string $key): int
    {
        $result = $this->getConnection()->hLen($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function hMGet(string $key, array $fields): array
    {
        $result = $this->getConnection()->hMGet($key, $fields);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function hMSet(string $key, array $keysAndValues): bool
    {
        $result = $this->getConnection()->hMset($key, $keysAndValues);

        return $this->multi ? true : $result;
    }

    /**
     * @inheritDoc
     */
    public function hScan(string $key, int $cursor, string $pattern = '', int $count = 0): array
    {
        $result = $this->getConnection()->hScan($key, $cursor, $pattern, $count);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function hSet(string $key, string $field, $value): bool
    {
        $result = $this->getConnection()->hSet($key, $field, $value);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function hSetNx(string $key, string $field, $value): bool
    {
        $result = $this->getConnection()->hSetNx($key, $field, $value);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function hStrLen(string $key, string $field): int
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function hVals(string $key): array
    {
        $result = $this->getConnection()->hVals($key);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function incr(string $key): int
    {
        $result = $this->getConnection()->incr($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function incrBy(string $key, int $value): int
    {
        $result = $this->getConnection()->incrBy($key, $value);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function incrByFloat(string $key, float $value): float
    {
        $result = $this->getConnection()->incrByFloat($key, $value);

        return $this->multi ? 0.0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function info(): array
    {
        return $this->getConnection()->info();
    }

    /**
     * @inheritDoc
     */
    public function keys(string $pattern): array
    {
        $result = $this->getConnection()->keys($pattern);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function lIndex(string $key, int $index): string
    {
        $result = $this->getConnection()->lIndex($key, $index);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function lInsert(string $key, int $index, string $pivot, $value): bool
    {
        $result = $this->getConnection()->lInsert($key, $index, $pivot, $value);

        return $this->multi ? false : (-1 !== $result);
    }

    /**
     * @inheritDoc
     */
    public function lLen(string $key): int
    {
        $result = $this->getConnection()->lLen($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function lPop(string $key)
    {
        $result = $this->getConnection()->lPop($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function lPush(string $key, $value): bool
    {
        $result = $this->getConnection()->lPush($key, $value);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function lPushNx(string $key, $value): bool
    {
        $result = $this->getConnection()->lPushx($key, $value);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function lPushX(string $key, $value): bool
    {
        $result = $this->getConnection()->lPushx($key, $value);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function lRange(string $key, int $start, int $stop): array
    {
        $result = $this->getConnection()->lRange($key, $start, $stop);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function lRem(string $key, $reference, int $count): int
    {
        $result = $this->getConnection()->lRem($key, $reference, $count);
        if (false === $result) {
            return 0;
        }

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function lSet(string $key, int $index, $value): bool
    {
        $result = $this->getConnection()->lSet($key, $index, $value);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function lTrim(string $key, int $start, int $stop): array
    {
        $result = $this->getConnection()->lTrim($key, $start, $stop);
        if (false === $result) {
            return [];
        }

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function lastSave(): int
    {
        $result = $this->getConnection()->lastSave();

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function mGet(array $keys): array
    {
        $result = $this->getConnection()->mget($keys);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function mSet(array $keysAndValues): bool
    {
        $result = $this->getConnection()->mset($keysAndValues);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function mSetNx(array $keysAndValues): bool
    {
        $result = $this->getConnection()->msetnx($keysAndValues);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function move(string $key, int $db): bool
    {
        $result = $this->getConnection()->move($key, $db);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function multi(): MultiRedisInterface
    {
        $this->getConnection()->multi(\Redis::MULTI);
        $this->multi = true;

        return new TransactionRedis($this);
    }

    /**
     * @inheritDoc
     */
    public function pExpire(string $key, int $milliseconds): bool
    {
        $result = $this->getConnection()->pExpire($key, $milliseconds);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function pExpireAt(string $key, int $milliseconds): bool
    {
        $result = $this->getConnection()->pExpireAt($key, $milliseconds);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function pSetEx(string $key, int $milliseconds, $value): bool
    {
        $result = $this->getConnection()->psetex($key, $milliseconds, $value);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function pSubscribe(string $pattern)
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function pTtl(string $key): int
    {
        $result = $this->getConnection()->pttl($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function pUnSubscribe(string $pattern)
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function persist(string $key): bool
    {
        $result = $this->getConnection()->persist($key);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function pfAdd(string $key, $element): bool
    {
        $result = $this->getConnection()->pfAdd($key, $element);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function pfCount(string $key): int
    {
        $result = $this->getConnection()->pfCount($key);

        return $this->multi ? 1 : $result;
    }

    /**
     * @inheritDoc
     */
    public function pfMerge(string $destination, array $keys): bool
    {
        $this->getConnection()->pfMerge($destination, $keys);

        return false;
    }

    /**
     * @inheritDoc
     */
    public function ping(string $message = ''): string
    {
        $result = $this->getConnection()->ping();

        return $this->multi ? $message : $result;
    }

    /**
     * @inheritDoc
     */
    public function pipeline(): MultiRedisInterface
    {
        $this->getConnection()->multi(\Redis::PIPELINE);
        $this->multi = true;

        return new PipelineRedis($this);
    }

    /**
     * @inheritDoc
     */
    public function pubSub(string $subCommand, array $arguments)
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function publish(string $channel, string $message): bool
    {
        $this->getConnection()->publish($channel, $message);

        return $this->multi ? false : true;
    }

    /**
     * @inheritDoc
     */
    public function quit(): RedisInterface
    {
        $this->getConnection()->close();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function rPop(string $key)
    {
        $result = $this->getConnection()->rPop($key);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function rPopLPush(string $source, string $destination)
    {
        $result = $this->getConnection()->rpoplpush($source, $destination);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function rPush(string $key, $value): bool
    {
        $result = $this->getConnection()->rPush($key, $value);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function rPushX(string $key, $value): bool
    {
        $result = $this->getConnection()->rPushx($key, $value);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function randomKey(): string
    {
        $result = $this->getConnection()->randomKey();

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function rename(string $oldName, string $newName): bool
    {
        $result = $this->getConnection()->rename($oldName, $newName);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function renameNx(string $oldName, string $newName): bool
    {
        $result = $this->getConnection()->renameNx($oldName, $newName);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function runQuery($query, array $bindParams = [], array $bindTypes = []): CursorInterface
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function sAdd(string $key, string $member): bool
    {
        $result = $this->getConnection()->sAdd($key, $member);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function sCard(string $key): int
    {
        $result = $this->getConnection()->sCard($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function sDiff(array $keys): array
    {
        $result = $this->getConnection()->sDiff(...$keys);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sDiffStore(string $destination, array $sources): int
    {
        $result = $this->getConnection()->sDiffStore($destination, ...$sources);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function sInter(array $keys): array
    {
        $result = $this->getConnection()->sInter(...$keys);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sInterStore(string $destination, array $keys): int
    {
        $result = $this->getConnection()->sInterStore($destination, ...$keys);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function sIsMember(string $key, string $member): bool
    {
        $result = $this->getConnection()->sIsMember($key, $member);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function sMembers(string $key): array
    {
        $result = $this->getConnection()->sMembers($key);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sMove($member, string $destination, string $source): bool
    {
        $result = $this->getConnection()->sMove($source, $destination, $member);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function sPop(string $key, int $count = 1): array
    {
        $result = $this->getConnection()->sPop($key);

        return $this->multi ? [] : [$result];
    }

    /**
     * @inheritDoc
     */
    public function sRandMember(string $key, int $count = 1): array
    {
        $result = $this->getConnection()->sRandMember($key, $count);

        return $this->multi ? [] : is_string($result) ? [$result] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sRem(string $key, string $member): bool
    {
        $result = $this->getConnection()->sRem($key, $member);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function sScan(string $key, int $cursor, string $pattern = '', int $count = 0): array
    {
        $result = $this->getConnection()->sScan($key, $cursor, $pattern, $count);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sUnion(array $keys): array
    {
        $result = $this->getConnection()->sUnion(...$keys);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function sUnionStore(string $destination, array $keys): int
    {
        $result = $this->getConnection()->sUnionStore($destination, ...$keys);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function save(): bool
    {
        $result = $this->getConnection()->save();

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function scan(int $cursor, string $pattern = '', int $count = 0): array
    {
        $result = $this->getConnection()->scan($cursor, $pattern, $count);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function select(int $database): bool
    {
        $result = $this->getConnection()->select($database);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function set($key, $value, $ttl = null): bool
    {
        $result = $this->getConnection()->set($key, $value, (int)$ttl);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function setBit(string $key, int $offset, int $value): int
    {
        $result = $this->getConnection()->setBit($key, $offset, $value);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function setEx(string $key, int $ttl, $value): bool
    {
        $result = $this->getConnection()->setex($key, $ttl, $value);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function setNx(string $key, $value): bool
    {
        $result = $this->getConnection()->setnx($key, $value);

        return $this->multi ? false : $result;
    }

    /**
     * @inheritDoc
     */
    public function setRange(string $key, int $offset, $value): string
    {
        $result = $this->getConnection()->setRange($key, $offset, $value);

        return $this->multi ? '' : $result;
    }

    /**
     * @inheritDoc
     */
    public function sort(
        string $key,
        string $pattern = '',
        int $limit = 0,
        int $offset = 0,
        string $destination
    ): array {
        $result = $this->getConnection()->sort(
            $key,
            array_merge(
                [
                    'by'     => $pattern,
                    'limit'  => $limit,
                    'offset' => $offset,
                    'store'  => $destination,
                ],
                []
            )
        );

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function strLen(string $key): int
    {
        $result = $this->getConnection()->strlen($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function swapDb(int $source, int $destination): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function time(): float
    {
        $result = $this->getConnection()->time();

        return $this->multi ? 0.0 : (float)implode('.', $result);
    }

    /**
     * @inheritDoc
     */
    public function touch(string $key): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function ttl(string $key): int
    {
        $result = $this->getConnection()->ttl($key);
        if (false === $result) {
            return 0;
        }

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function type(string $key): string
    {
        $result = $this->getConnection()->type($key);

        return $this->multi ? '' : (string)$result;
    }

    /**
     * @inheritDoc
     */
    public function unlink(string $key): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function unwatch(): RedisInterface
    {
        $this->getConnection()->unwatch();

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function watch(string $key): RedisInterface
    {
        $this->getConnection()->watch($key);

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function zAdd(string $key, int $score, $value): bool
    {
        $result = $this->getConnection()->zAdd($key, $score, $value);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function zAddCond(string $key, string $mode, int $score, $value): bool
    {
        if (false !== $this->getConnection()
                           ->evalSha(
                               sha1(CRedisConnection::scripts['zAddCond']),
                               [
                                   $this->getConnection()->_prefix(
                                       $key
                                   ),
                                   $mode,
                                   $score,
                                   $value,
                               ],
                               1
                           )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function zAddMod(string $key, string $mode, int $score, $value): bool
    {
        if (false !== $this->getConnection()
                           ->evalSha(
                               sha1(CRedisConnection::scripts['zAddXXNX']),
                               [
                                   $this->getConnection()->_prefix(
                                       $key
                                   ),
                                   $mode,
                                   $score,
                                   $value,
                               ],
                               1
                           )
        ) {
            return true;
        }

        return false;
    }

    /**
     * @inheritDoc
     */
    public function zCard(string $key): int
    {
        $result = $this->getConnection()->zCard($key);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zCount(string $key, int $fromScore, int $toScore): int
    {
        if (-1 === $fromScore) {
            $fromScore = '-inf';
        }

        if (-1 === $toScore) {
            $toScore = '+inf';
        }

        $result = $this->getConnection()->zCount($key, (string)$fromScore, (string)$toScore);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zDelete(string $key, string $member): bool
    {
        $result = $this->getConnection()->zDelete($key, $member);

        return $this->multi ? false : (1 === $result);
    }

    /**
     * @inheritDoc
     */
    public function zDeleteRangeByScore(string $key, int $fromScore, int $toScore): int
    {
        return $this->zRemRangeByScore($key, $fromScore, $toScore);
    }

    /**
     * @inheritDoc
     */
    public function zIncrBy(string $key, float $score, string $member): float
    {
        $result = $this->getConnection()->zIncrBy($key, $score, $member);

        return $this->multi ? 0.0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRange(string $key, int $from, int $to): array
    {
        $result = $this->getConnection()->zRange($key, $from, $to);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRangeByScore(string $key, int $fromScore, int $toScore, array $options = []): array
    {
        $cRedisOptions[self::WITH_SCORES] = array_key_exists(self::WITH_SCORES, $options)
            ? $options[self::WITH_SCORES]
            : false;

        if (array_key_exists(self::ZRANGE_OFFSET, $options)) {
            $cRedisOptions[self::ZRANGE_LIMIT][] = $options[self::ZRANGE_OFFSET];
        }

        if (array_key_exists(self::ZRANGE_LIMIT, $options)) {
            $cRedisOptions[self::ZRANGE_LIMIT][] = $options[self::ZRANGE_LIMIT];
        }

        $result = $this->getConnection()->zRangeByScore($key, $fromScore, $toScore, $cRedisOptions);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRank(string $key, string $member): int
    {
        $result = $this->getConnection()->zRank($key, $member);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRemRangeByRank(string $key, int $start, int $stop): int
    {
        $result = $this->getConnection()->zRemRangeByRank($key, $start, $stop);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRemRangeByScore(string $key, int $fromScore, int $toScore): int
    {
        $result = $this->getConnection()->zRemRangeByScore($key, $fromScore, $toScore);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRevRange(string $key, int $from, int $to): array
    {
        $result = $this->getConnection()->zRevRange($key, $from, $to);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRevRangeByScore(string $key, int $fromScore, int $toScore, array $options = []): array
    {
        $cRedisOptions[self::WITH_SCORES] = array_key_exists(self::WITH_SCORES, $options) ? true : false;

        if (array_key_exists(self::ZRANGE_OFFSET, $options)) {
            $cRedisOptions[self::ZRANGE_LIMIT][] = $options[self::ZRANGE_OFFSET];
        }

        if (array_key_exists(self::ZRANGE_LIMIT, $options)) {
            $cRedisOptions[self::ZRANGE_LIMIT][] = $options[self::ZRANGE_LIMIT];
        }

        $result = $this->getConnection()->zRevRangeByScore($key, $fromScore, $toScore, $cRedisOptions);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRevRangeByScoreLimit(string $key, int $fromScore, int $toScore, int $offset, int $count): array
    {
        return $this->zRevRangeByScore(
            $key,
            $fromScore,
            $toScore,
            [
                self::ZRANGE_LIMIT  => $count,
                self::ZRANGE_OFFSET => $offset,
            ]
        );
    }

    /**
     * @inheritDoc
     */
    public function zRevRangeWithScores(string $key, int $from, int $to): array
    {
        $result = $this->getConnection()->zRevRange($key, $from, $to, true);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRevRank(string $key, string $member): int
    {
        $result = $this->getConnection()->zRevRank($key, $member);

        return $this->multi ? 0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function zScore(string $key, string $member): float
    {
        $result = $this->getConnection()->zScore($key, $member);

        return $this->multi ? 0.0 : $result;
    }

    /**
     * @inheritDoc
     */
    public function subscribe(string $channel): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function unSubscribe(string $channel): bool
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function zInterStore(string $destination, array $sources): int
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function zLexCount(string $key, $from, $to): int
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function zRangeByLex(string $key, $from, $to): array
    {
        $result = $this->getConnection()->zRangeByLex($key, $from, $to);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zRem(string $key, string $member): bool
    {
        $result = $this->getConnection()->zRem($key, $member);

        return $this->multi ? false : (false !== $result);
    }

    /**
     * @inheritDoc
     */
    public function zRemRangeByLex(string $key, $from, $to): int
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }

    /**
     * @inheritDoc
     */
    public function zRevRangeByLex(string $key, $from, $to): array
    {
        $result = $this->getConnection()->zRevRangeByLex($key, $from, $to);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zScan(string $key, int $cursor, string $pattern = '', int $count = 0): array
    {
        $result = $this->getConnection()->zScan($key, $cursor, $pattern, $count);

        return $this->multi ? [] : $result;
    }

    /**
     * @inheritDoc
     */
    public function zUnionStore(string $destination, array $sources): int
    {
        throw new NotImplementedRedisException($this, __METHOD__);
    }
}