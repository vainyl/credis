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

namespace Vainyl\CRedis\Exception;

use Vainyl\Redis\Exception\AbstractRedisException;
use Vainyl\Redis\RedisInterface;

/**
 * Class NotImplementedRedisException
 *
 * @author Taras P. Girnyk <taras.p.gyrnik@gmail.com>
 */
class NotImplementedRedisException extends AbstractRedisException
{
    /**
     * NotImplementedRedisException constructor.
     *
     * @param RedisInterface $redis
     * @param string         $method
     */
    public function __construct(RedisInterface $redis, string $method)
    {
        parent::__construct($redis, sprintf('Method %s is not implemented', $method));
    }
}