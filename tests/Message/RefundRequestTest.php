<?php

namespace Omnipay\Btce\Message;

use Guzzle\Plugin\Mock\MockPlugin;
use Omnipay\Tests\TestCase;

class RefundRequestTest extends TestCase
{
    /**
     *
     * @var PurchaseRequest
     *
     */
    private $request;

    protected function setUp()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getMockHttpResponse('RefundSuccess.txt'));
        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $this->request = new RefundRequest($httpClient, $this->getHttpRequest());

        $this->request->setPayeeAccount('PayeeAccount');
        $this->request->setAmount('10.00');
        $this->request->setDescription('Description');
        $this->request->setCurrency('RUB');
    }

    public function testGetData()
    {
        $data = $this->request->getData();
        $expectedData = [
            'method' => 'CreateCoupon',
            'amount' => '10.00',
            'currency' => 'RUR',
            'receiver' => 'PayeeAccount',
        ];
        $this->assertEquals($expectedData['method'], $data['method']);
        $this->assertEquals($expectedData['amount'], $data['amount']);
        $this->assertEquals($expectedData['currency'], $data['currency']);
        $this->assertEquals($expectedData['receiver'], $data['receiver']);
    }

    public function testCreateSign()
    {
        $data = [
            'method' => 'CreateCoupon',
            'amount' => '10.00',
            'currency' => 'RUR',
            'receiver' => 'PayeeAccount',
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
        $this->assertEquals('coupon', $response->getCoupon());
    }

    public function testSendError()
    {
        $mockPlugin = new MockPlugin();
        $mockPlugin->addResponse($this->getMockHttpResponse('RefundError.txt'));
        $httpClient = $this->getHttpClient();
        $httpClient->addSubscriber($mockPlugin);

        $request = new RefundRequest($httpClient, $this->getHttpRequest());
        $request->setPayeeAccount('PayeeAccount');
        $request->setAmount('10.00');
        $request->setDescription('Description');
        $request->setCurrency('USD');

        $response = $request->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertEquals('Error string', $response->getMessage());
    }

}