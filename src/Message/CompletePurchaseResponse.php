<?php

namespace Omnipay\Btce\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{
    public function isSuccessful()
    {
        return ($this->data->success == 1) ? true : false;
    }

    public function isCancelled()
    {
        return ($this->data->success == 0) ? true : false;
    }

    public function getTransactionId()
    {
        return intval($this->data->return->transID);
    }

    public function getAmount()
    {
        return floatval($this->data->return->couponAmount);
    }

    public function getCurrency()
    {
        return floatval($this->data->return->couponCurrency);
    }

    public function getMessage()
    {
        return $this->data->error;
    }
}
