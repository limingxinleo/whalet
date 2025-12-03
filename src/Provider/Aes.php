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

class Aes extends Provider
{
    private const AES = 'AES';

    private const MODE = 'aes-128-ecb'; // 对应Java的AES/ECB/PKCS5Padding

    private const BLOCK_SIZE = 16;

    /**
     * 生成符合AES要求的128位密钥（Base64编码）.
     */
    public function generateAesKey(): string
    {
        // 生成16字节（128位）的随机密钥
        $keyBytes = random_bytes(16);
        return base64_encode($keyBytes);
    }

    /**
     * AES加密.
     * @param string $keyStr Base64编码的密钥
     * @param string $plaintext 明文
     * @return string 加密后的Base64字符串
     */
    public function encrypt(string $keyStr, string $plaintext): string
    {
        $key = base64_decode($keyStr);
        // 使用PKCS5填充（与Java的PKCS5Padding一致）
        // $blockSize = openssl_cipher_iv_length(self::MODE);
        $blockSize = self::BLOCK_SIZE;
        $padding = $blockSize - (strlen($plaintext) % $blockSize);
        $plaintext .= str_repeat(chr($padding), $padding);

        $ciphertext = openssl_encrypt(
            $plaintext,
            self::MODE,
            $key,
            OPENSSL_RAW_DATA, // 输出原始二进制数据
            '' // ECB模式不需要IV
        );

        return base64_encode($ciphertext);
    }

    /**
     * AES解密.
     * @param string $keyStr Base64编码的密钥
     * @param string $ciphertext 加密后的Base64字符串
     * @return string 解密后的明文
     */
    public function decrypt(string $keyStr, string $ciphertext): string
    {
        $key = base64_decode($keyStr);
        $cipherBytes = base64_decode($ciphertext);

        $plaintext = openssl_decrypt(
            $cipherBytes,
            self::MODE,
            $key,
            OPENSSL_RAW_DATA, // 输入原始二进制数据
            '' // ECB模式不需要IV
        );

        // 去除PKCS5填充
        $padding = ord($plaintext[strlen($plaintext) - 1]);
        return substr($plaintext, 0, -$padding);
    }
}
