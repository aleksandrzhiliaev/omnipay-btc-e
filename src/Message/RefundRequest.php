<?php

namespace Omnipay\Btce\Message;


class RefundRequest extends AbstractRequest
{
    private $endpoint = 'https://wex.nz/tapi/';

    public function getData()
    {
        $this->validate('amount');

        return [
            'method' => 'CreateCoupon',
            'amount' => $this->getAmount(),
            'currency' => $this->getCurrency(),
            'receiver' => $this->getPayeeAccount(),
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

        return $this->response = new RefundResponse($this, $json);
    }

    public function createSign($data)
    {
        $postDataString = http_build_query($data, '', '&');
        $sign = hash_hmac("sha512", $postDataString, $this->getSecret());

        return $sign;
    }

}
