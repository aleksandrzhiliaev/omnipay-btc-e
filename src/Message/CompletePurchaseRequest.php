<?php

namespace Omnipay\Btce\Message;


class CompletePurchaseRequest extends AbstractRequest
{
    private $endpoint = 'https://wex.nz/tapi/';

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
        $sign = $this->createSign($data);

        $headers = [
            'Content-Type' => 'multipart/form-data',
            'Sign' => $sign,
            'Key' => $this->getAccount(),
        ];

        $httpResponse = $this->httpClient->post($this->endpoint, $headers, $data)->send();
        $json = json_decode($httpResponse->getBody(true));

        return $this->response = new CompletePurchaseResponse($this, $json);
    }

    public function createSign($data)
    {
        $postDataString = http_build_query($data, '', '&');
        $sign = hash_hmac("sha512", $postDataString, $this->getSecret());

        return $sign;
    }

}
