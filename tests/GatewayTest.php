<?php

namespace Omnipay\Btce;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
    }

    public function testCompletePurchase()
    {
        $request = $this->gateway->completePurchase();
        $this->assertInstanceOf('\Omnipay\Btce\Message\CompletePurchaseRequest', $request);
    }

    public function testRefund()
    {
        $request = $this->gateway->refund([
            'payeeAccount' => 'Login',
            'amount' => 1.0,
            'description' => 'Testing btc-e',
            'currency' => 'USD',
        ]);
        $this->assertInstanceOf('\Omnipay\Btce\Message\RefundRequest', $request);
        $this->assertSame('Login', $request->getPayeeAccount());
        $this->assertSame('1.00', $request->getAmount());
        $this->assertSame('USD', $request->getCurrency());
    }
}