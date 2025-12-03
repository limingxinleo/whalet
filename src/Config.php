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

class Config
{
    /**
     * @param int $partnerId 合作方ID
     * @param string $clientId 应用ID
     * @param string $secretKey 应用密钥
     * @param string $hmacKey 签名key
     * @param string $aesKey 敏感字段加密key
     * @param string $webhookUrl 通知地址
     */
    public function __construct(
        public int $partnerId,
        public string $clientId,
        public string $secretKey,
        public string $hmacKey,
        public string $aesKey,
        public string $webhookUrl,
        public string $environment = 'prod',
    ) {
    }

    public static function from(array $config): Config
    {
        return new Config(
            $config['partner_id'] ?? 0,
            $config['client_id'] ?? '',
            $config['secret_key'] ?? '',
            $config['hmac_key'] ?? '',
            $config['aes_key'] ?? '',
            $config['webhook_url'] ?? '',
            $config['environment'] ?? '',
        );
    }

    public function isProd(): bool
    {
        return $this->environment === 'prod';
    }

    public function getBaseUri(): string
    {
        return $this->isProd() ? 'https://open.whalet.com' : 'https://test-open.whalet.com';
    }
}
