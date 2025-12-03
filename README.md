# Whalet支付网关SDK

## 如何使用

### Hyperf框架

增加配置 `config/autoload/whalet.php`

```php
return [
    'partner_id' => 0,
    'client_id' => '',
    'secret_key' => '',
    'hmac_key' => '',
    'aes_key' => '',
    'webhook_url' => '',
    'environment' => 'prod',
];
```