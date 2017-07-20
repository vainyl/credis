<?php
/**
 * Vainyl
 *
 * PHP Version 7
 *
 * @package   pdo
 * @license   https://opensource.org/licenses/MIT MIT License
 * @link      https://vainyl.com
 */
declare(strict_types=1);

namespace Vainyl\CRedis;

use Vainyl\Connection\AbstractConnection;

/**
 * Class CRedisConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class CRedisConnection extends AbstractConnection
{
    const scripts = [
        'zAddXXNX' => 'return redis.call(\'zAdd\', KEYS[1], ARGV[1], ARGV[2], ARGV[3])',
        // 185a09d32f70bd6274c081aafe6a2141aee0687e
        'zAddCond' => '
                    local score = redis.call("zScore", KEYS[1], ARGV[3]);
                    if score == false then
                        return redis.call("zAdd", KEYS[1], "CH", ARGV[2], ARGV[3]);
                    end
                    if (ARGV[1] == "LT" and score > ARGV[2]) or (ARGV[1] == "GT" and score < ARGV[2]) then
                        return redis.call("zAdd", KEYS[1], "XX", "CH", ARGV[2], ARGV[3]);
                    end

                    return 0;
        '
        // bb8049d9b393db5b35998e1ed05c0913bff0a683
    ];

    private $host;

    private $port;

    private $database;

    private $algorithm;

    private $password;

    private $serializer;

    /**
     * PdoConnection constructor.
     *
     * @param string $connectionName
     * @param        string string
     * @param string $host
     * @param int    $port
     * @param int    $database
     * @param string $password
     * @param string $algorithm
     * @param bool   $serializer
     */
    public function __construct(
        $connectionName,
        string $host,
        int $port,
        int $database,
        string $password,
        string $algorithm,
        bool $serializer
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->password = $password;
        $this->algorithm = $algorithm;
        $this->serializer = $serializer;
        parent::__construct($connectionName);
    }

    /**
     * @inheritDoc
     */
    public function doEstablish()
    {
        $redis = new \Redis();
        $redis->connect($this->host, $this->port);
        if ('' !== ($password = $this->getPassword())) {
            $redis->auth($password);
        }
        if ($this->serializer) {
            $redis->setOption(\Redis::OPT_SERIALIZER, $this->getSerializerValue());
        }
        $redis->select($this->database);

        foreach (self::scripts as $script) {
            if ([0] === $redis->script('exists', sha1($script))) {
                $redis->script('load', $script);
            }
        }

        return $redis;
    }

    /**
     * @return string
     */
    protected function getPassword(): string
    {
        if ('' === $this->algorithm) {
            return $this->password;
        }

        return hash($this->algorithm, $this->password);
    }

    /**
     * @return mixed
     */
    protected function getSerializerValue()
    {
        if (defined('Redis::SERIALIZER_IGBINARY') && extension_loaded('igbinary')) {
            return \Redis::SERIALIZER_IGBINARY;
        }

        return \Redis::SERIALIZER_PHP;
    }
}
