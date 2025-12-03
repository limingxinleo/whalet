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

use Psr\Container\ContainerInterface;
use Psr\SimpleCache\CacheInterface;
use Whalet\Provider\Acquiring;
use Whalet\Provider\Aes;
use Whalet\Provider\Auth;
use Whalet\Provider\Http;

/**
 * @see https://docs.whalet.com/doc-7059665
 */
class Client implements ClientInterface
{
    public Aes $aes;

    public Auth $auth;

    public Http $http;

    public Acquiring $acquiring;

    public ?CacheInterface $cache = null;

    public function __construct(public Config $config, ?ContainerInterface $container = null)
    {
        $this->aes = new Aes($this);
        $this->auth = new Auth($this);
        $this->http = new Http($this);
        $this->acquiring = new Acquiring($this);

        if ($container?->has(CacheInterface::class)) {
            $this->cache = $container?->get(CacheInterface::class);
        }
    }
}
