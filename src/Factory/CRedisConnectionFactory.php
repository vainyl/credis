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

/**
 * Class CRedisConnectionFactory
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class CRedisConnectionFactory extends AbstractIdentifiable implements ConnectionFactoryInterface
{
    /**
     * @inheritDoc
     */
    public function createConnection(string $name, array $configData): ConnectionInterface
    {
        return new PdoConnection(
            $name,
            $configData['engine'],
            $configData['host'],
            $configData['port'],
            $configData['database'],
            $configData['user'],
            $configData['password'],
            []
        );
    }
}
