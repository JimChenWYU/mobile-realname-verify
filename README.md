# mobile-realname-verify

手机号实名认证

## Requirement

- PHP >= 5.5

## Installing

```shell
$ composer require jimchen/mobile-realname-verify -vvv
```

## Usage

```php
use JimChen\Authentication\Authentication;
use JimChen\Authentication\Gateways\JuheGateway;

$juhe = \JimChen\Authentication\Authentication::juhe([
    'key'    => 'your key',
    'openid' => 'your openid',
    'type'   => JuheGateway::COMMON
]);

// 手机号码实名认证
$juhe->verify('Real Name', 'ID Card', 'Mobile Number');
```

## License

MIT
