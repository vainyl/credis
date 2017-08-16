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
use Vainyl\Redis\RedisScriptInterface;

/**
 * Class CRedisConnection
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class CRedisConnection extends AbstractConnection
{
    private $host;

    private $port;

    private $database;

    private $algorithm;

    private $password;

    private $serializer;

    private $scripts;

    /**
     * PdoConnection constructor.
     *
     * @param string                 $connectionName
     * @param string                 $host
     * @param int                    $port
     * @param int                    $database
     * @param string                 $password
     * @param string                 $algorithm
     * @param bool                   $serializer
     * @param RedisScriptInterface[] $scripts
     */
    public function __construct(
        $connectionName,
        string $host,
        int $port,
        int $database,
        string $password,
        string $algorithm,
        bool $serializer,
        \Traversable $scripts
    ) {
        $this->host = $host;
        $this->port = $port;
        $this->database = $database;
        $this->password = $password;
        $this->algorithm = $algorithm;
        $this->serializer = $serializer;
        $this->scripts = $scripts;
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
            $redis->setOption(\Redis::OPT_SERIALIZER, (string)$this->getSerializerValue());
        }
        $redis->select($this->database);

        foreach ($this->scripts as $script) {
            if ([0] === $redis->script('exists', $script->getId())) {
                $redis->script('load', $script->__toString());
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
