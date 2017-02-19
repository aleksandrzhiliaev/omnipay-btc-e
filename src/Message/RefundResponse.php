<?php

namespace Omnipay\Btce\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;
use Omnipay\Btce\Support\Helpers;

class RefundResponse extends AbstractResponse
{
    protected $redirectUrl;
    protected $message;
    protected $success;
    protected $coupon;

    public function __construct(RequestInterface $request, $data)
    {
        $this->request = $request;
        $this->data = $data;
        $this->success = false;
        $this->parseResponse($data);
    }

    public function isSuccessful()
    {
        return $this->success;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getCoupon()
    {
        return $this->coupon;
    }

    private function parseResponse($data)
    {
        if ($data == null) {
            $this->success = false;
            $this->message = 'Null data, incorrect request';
            return false;
        }

        if (isset($data->success) && $data->success == 1) {
            $this->success = true;
            return true;
        }

        $this->message = $data->error;
        $this->success = false;
    }

}
