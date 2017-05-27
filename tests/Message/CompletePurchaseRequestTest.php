<?php

namespace Omnipay\Advcash\Message;

use Guzzle\Plugin\Mock\MockPlugin;
use Omnipay\Btce\Message\CompletePurchaseRequest;
use Omnipay\Tests\TestCase;

class CompletePurchaseRequestTest extends TestCase
{
    private $request;

    protected function setUp()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getMockHttpResponse('CompletePurchaseSuccess.txt'));
        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $this->request = new CompletePurchaseRequest($httpClient, $this->getHttpRequest());
        $this->request->setAccount('Account');
        $this->request->setSecret('Secret');
        $this->request->setCurrency('Currency');
        $this->request->setAmount('10.00');
        $this->request->setCoupon('Coupon');
        $this->request->setTransactionId(1);
    }

    public function testGetData()
    {
        $data = $this->request->getData();

        $expected = [
            'method' => 'RedeemCoupon',
            'coupon' => 'Coupon',
        ];

        $this->assertEquals($expected['method'], $data['method']);
        $this->assertEquals($expected['coupon'], $data['coupon']);
    }

    public function testCreateSign()
    {
        $data = [
            'method' => 'RedeemCoupon',
            'coupon' => 'Coupon',
            'nonce' => time(),
        ];

        $dataString = http_build_query($data, '', '&');
        $expectedSign = hash_hmac("sha512", $dataString, $this->request->getSecret());

        $sign = $this->request->createSign($data);
        $this->assertEquals($expectedSign, $sign);
    }

    public function testSendSuccess()
    {
        $response = $this->request->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertEquals('USD', $response->getCurrency());
        $this->assertEquals('10.00', $response->getAmount());
        $this->assertEquals(1, $response->getTransactionId());
    }

    public function testSendError()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getMockHttpResponse('CompletePurchaseError.txt'));
        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $request = new CompletePurchaseRequest($httpClient, $this->getHttpRequest());
        $request->setAccount('Account');
        $request->setSecret('Secret');
        $request->setCurrency('Currency');
        $request->setAmount('10.00');
        $request->setCoupon('Coupon');


        $response = $request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Error string', $response->getMessage());
    }
}