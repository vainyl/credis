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

use Vainyl\Cache\CacheInterface;
use Vainyl\Cache\Factory\CacheFactoryInterface;
use Vainyl\Core\AbstractIdentifiable;
use Vainyl\CRedis\CRedisDatabase;
use Vainyl\Database\DatabaseInterface;
use Vainyl\Database\Factory\DatabaseFactoryInterface;

/**
 * Class CRedisDatabaseFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class CRedisDatabaseFactory extends AbstractIdentifiable implements DatabaseFactoryInterface, CacheFactoryInterface
{
    private $connectionStorage;

    /**
     * CRedisDatabaseFactory constructor.
     *
     * @param \ArrayAccess $connectionStorage
     */
    public function __construct(\ArrayAccess $connectionStorage)
    {
        $this->connectionStorage = $connectionStorage;
    }

    /**
     * @inheritDoc
     */
    public function createCache(string $cacheName, string $connectionName, array $options = []): CacheInterface
    {
        return $this->createDatabase($cacheName, $connectionName, $options);
    }

    /**
     * @param string $databaseName
     * @param string $connectionName
     * @param array  $options
     *
     * @return CRedisDatabase
     */
    public function createDatabase(
        string $databaseName,
        string $connectionName,
        array $options = []
    ): DatabaseInterface {
        return new CRedisDatabase($databaseName, $this->connectionStorage[$connectionName]);
    }
}
