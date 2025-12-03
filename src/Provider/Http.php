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

use GuzzleHttp;
use GuzzleHttp\HandlerStack;
use Hyperf\Codec\Json;
use Throwable;
use Whalet\Exception\RequestException;

class Http extends Provider
{
    public function client(): GuzzleHttp\Client
    {
        return new GuzzleHttp\Client([
            'base_uri' => $this->whalet->config->getBaseUri(),
            'handler' => HandlerStack::create(),
            'timeout' => 5,
        ]);
    }

    public function request(string $method, string $uri = '', array $options = []): array
    {
        $token = $this->whalet->auth->token();

        $options['headers']['Authorization'] = sprintf('%s %s', $token['tokenType'], $token['token']);

        try {
            return $this->rawRequest($method, $uri, $options);
        } catch (Throwable $exception) {
            // TODO: Token 非法或者失效错误码
            if ($exception instanceof RequestException && $exception->errorCode === 'TokenInvalid') {
                $token = $this->whalet->auth->token(true);

                $options['headers']['Authorization'] = sprintf('%s %s', $token['tokenType'], $token['token']);

                return $this->rawRequest($method, $uri, $options);
            }

            throw $exception;
        }
    }

    public function rawRequest(string $method, string $uri = '', array $options = []): array
    {
        $options['headers'] = array_merge(
            [
                'partnerId' => $this->whalet->config->partnerId,
                'requestNo' => uniqid(),
                'timestamp' => (int) (microtime(true) * 1000),
            ],
            $options['headers'] ?? []
        );

        $response = $this->client()->request($method, $uri, $options);

        $result = Json::decode((string) $response->getBody());
        if ($result['code'] !== 'SUCCESS') {
            throw new RequestException($result['message'], $result['code']);
        }

        return $result['data'];
    }
}
