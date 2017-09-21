# omnipay-btc-e (wex-nz)
[![Build Status](https://travis-ci.org/aleksandrzhiliaev/omnipay-btc-e.svg?branch=master)](https://travis-ci.org/aleksandrzhiliaev/omnipay-btc-e)
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/95c32cb06d414446a6a3e960f48152e5)](https://www.codacy.com/app/sassoftinc/omnipay-btc-e?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=aleksandrzhiliaev/omnipay-btc-e&amp;utm_campaign=Badge_Grade)
[![Latest Stable Version](https://poser.pugx.org/aleksandrzhiliaev/omnipay-btc-e/v/stable)](https://packagist.org/packages/aleksandrzhiliaev/omnipay-btc-e)
[![Total Downloads](https://poser.pugx.org/aleksandrzhiliaev/omnipay-btc-e/downloads)](https://packagist.org/packages/aleksandrzhiliaev/omnipay-btc-e)

BTC-E (WEX.NZ) API gateway for [Omnipay](https://github.com/thephpleague/omnipay) payment processing library.

[Omnipay](https://github.com/omnipay/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements Advcash support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "aleksandrzhiliaev/omnipay-btc-e": "*"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* BTC-E (WEX.NZ) codes

For general usage instructions, please see the main [Omnipay](https://github.com/omnipay/omnipay)
repository. See also the [WEX.NZ Documentation](https://wex.nz/api/3/docs#main)

## Example
1. Validate webhook:
```php
$gateway = Omnipay::create('Btce');

$gateway->setAccount('');
$gateway->setSecret('');

try {
    $gateway->setCoupon('WEXUSD69AA4BBX1UAZ32BPLKBR0QZTX5AENVNFWNZHNQDZ');
    
    $response = $gateway->completePurchase()->send();
    $success = $response->isSuccessful();
    if ($success) {
        $transactionId = $response->getTransactionId();
        $amount = $response->getAmount();
        $currency = $response->getCurrency();
    } else {
        // $response->getMessage();
    }
} catch (\Exception $e) {
  // check $e->getMessage()
}
```

2. Do refund
```php
try {
    $response = $gateway->refund(
        [
            'payeeAccount' => '',
            'amount' => 0.1,
            'description' => 'Testing advcash',
            'currency' => 'USD',
        ]
    )->send();

    if ($response->isSuccessful()) {
        print $response->getCoupon();
    } else {
        // check $response->getMessage();
    }

} catch (\Exception $e) {
    // check $e->getMessage();
}
```

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/aleksandrzhiliaev/omnipay-nixmoney/issues).
