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

namespace Vainyl\CRedis\Factory;

use Vainyl\Connection\ConnectionInterface;
use Vainyl\Connection\Factory\ConnectionFactoryInterface;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\CRedis\CRedisConnection;
use Vainyl\Redis\Storage\RedisScriptStorageInterface;

/**
 * Class CRedisConnectionFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class CRedisConnectionFactory extends AbstractIdentifiable implements ConnectionFactoryInterface
{
    private $scriptStorage;

    /**
     * CRedisConnectionFactory constructor.
     *
     * @param RedisScriptStorageInterface $scriptStorage
     */
    public function __construct(RedisScriptStorageInterface $scriptStorage)
    {
        $this->scriptStorage = $scriptStorage;
    }

    /**
     * @inheritDoc
     */
    public function createConnection(string $name, array $configData): ConnectionInterface
    {
        return new CRedisConnection(
            $name,
            $configData['host'],
            $configData['port'],
            $configData['database'],
            $configData['password'],
            $configData['algo'],
            $configData['serializer'],
            $this->scriptStorage
        );
    }
}
