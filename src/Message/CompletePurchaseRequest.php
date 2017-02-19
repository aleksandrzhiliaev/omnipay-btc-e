<?php

namespace Omnipay\Btce\Message;


use Omnipay\Common\Message\AbstractRequest;

class CompletePurchaseRequest extends AbstractRequest
{
    private $endpoint = 'https://btc-e.com/tapi/';

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getAccount()
    {
        return $this->getParameter('account');
    }

    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    public function getCoupon()
    {
        return $this->getParameter('coupon');
    }

    public function setCoupon($value)
    {
        return $this->setParameter('coupon', $value);
    }

    public function getData()
    {
        return [
            'method' => 'RedeemCoupon',
            'coupon' => $this->getCoupon(),
            'nonce' => time(),
        ];
    }

    public function sendData($data)
    {
        $postDataString = http_build_query($data, '', '&');
        $sign = hash_hmac("sha512", $postDataString, $this->getSecret());

        $headers = [
            'Content-Type' => 'multipart/form-data',
            'Sign' => $sign,
            'Key' => $this->getAccount(),
        ];

        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $data)->send();
        $json = json_decode($httpResponse->getBody(true));

        return $this->response = new CompletePurchaseResponse($this, $json);
    }

}
