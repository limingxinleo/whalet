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

namespace Whalet\Provider;

use JetBrains\PhpStorm\ArrayShape;

/**
 * @see https://docs.whalet.com/api-323605544
 */
class Auth extends Provider
{
    #[ArrayShape([
        'token' => 'string',
        'expire' => 'int',
        'tokenType' => 'string',
    ])]
    public function token(bool $refresh = false): array
    {
        $key = 'whalet:token';
        $token = $this->whalet->cache?->get($key);
        if (! $token || $refresh) {
            $token = $this->freshToken();
            $this->whalet->cache?->set($key, $token, $token['expire'] - 150);
        }

        return $token;
    }

    #[ArrayShape([
        'token' => 'string',
        'expire' => 'int',
        'tokenType' => 'string',
    ])]
    public function freshToken(): array
    {
        return $this->whalet->http->rawRequest('POST', '/v1/auth/token', [
            'json' => [
                'clientId' => $this->whalet->config->clientId,
                'secretKey' => $this->whalet->config->secretKey,
            ],
        ]);
    }
}
