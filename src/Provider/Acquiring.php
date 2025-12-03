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
 * 收单.
 * @see https://docs.whalet.com/folder-61728470
 */
class Acquiring extends Provider
{
    /**
     * Hosted Payment Page（托管支付页面）
     * 查询交易信息.
     * @see https://docs.whalet.com/api-322652158
     */
    public function queryPaymentInfo(int|string $userId, string $tradeId)
    {
        return $this->whalet->http->request('POST', '/v1/acquiring/payment/queryPaymentInfo', [
            'userId' => (string) $userId,
            'tradeId' => $tradeId,
        ]);
    }

    /**
     * Hosted Payment Page（托管支付页面）
     * 创建支付链接.
     * @see https://docs.whalet.com/api-322652157
     */
    #[ArrayShape([
        'tradeId' => 'string',
        'linkUrl' => 'string',
        'linkValidity' => 'string',
        'status' => 'string',
    ])]
    public function createPaymentPage(
        int|string $userId,
        int $amount,
        string $currency,
        string $acquiringShopId,
        #[ArrayShape([
            'name' => 'string',
            'email' => 'string',
            'country' => 'string',
        ])]
        array $customerInfo,
        #[ArrayShape([[
            'goodsName' => 'string',
            'unitPrice' => 'integer',
            'quantity' => 'integer',
        ]])]
        array $goodsInfoList,
        string $linkValidity,
        string $merTradeNo,
        #[ArrayShape([
            'description' => 'string',
            'logisticsFee' => 'integer',
            'taxFee' => 'integer',
            'discountAmount' => 'integer',
            'redirectUrl' => 'string',
        ])]
        array $extra = [],
    ): array {
        return $this->whalet->http->request('POST', '/v1/acquiring/payment/createPaymentPage', [
            'userId' => (string) $userId,
            'amount' => $amount,
            'currency' => $currency,
            'acquiringShopId' => $acquiringShopId,
            'customerInfo' => $customerInfo,
            'goodsInfoList' => $goodsInfoList,
            'linkValidity' => $linkValidity,
            'merTradeNo' => $merTradeNo,
            ...$extra,
        ]);
    }
}
