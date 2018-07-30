# mobile-realname-verify

极速API手机号实名认证，[官方文档](https://www.jisuapi.com/api/mobileverify/)

## Requirement

- PHP >= 5.5

## Installing

```shell
$ composer require jimchen/mobile-realname-verify -vvv
```

## Usage

```php
use JimChen\MobileRealNameVerify\MobileRealNameVerify;

$object = new MobileRealNameVerify([
    'appkey' => '6745abcdefg'
]);

// 获取证件类型
$object->type();

// 设置证件类型
$object->setTypeId('typeid');

// 手机号码实名认证
$object->verify('Mobile Number', 'Real Name', 'ID Card');
```

## License

MIT
