<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace Whalet;

use Hyperf\Contract\ConfigInterface;
use Psr\Container\ContainerInterface;

class ClientFactory
{
    public function __invoke(ContainerInterface $container): Client
    {
        $config = $container->get(ConfigInterface::class);

        return new Client(
            Config::from($config->get('whalet', [])),
            $container
        );
    }
}
