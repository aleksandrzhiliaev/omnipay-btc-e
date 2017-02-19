<?php

namespace Omnipay\Btce;

use Omnipay\Common\AbstractGateway;

class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Btce';
    }

    public function getAccount()
    {
        return $this->getParameter('account');
    }

    public function setAccount($value)
    {
        return $this->setParameter('account', $value);
    }

    public function getSecret()
    {
        return $this->getParameter('secret');
    }

    public function setSecret($value)
    {
        return $this->setParameter('secret', $value);
    }

    public function getCoupon()
    {
        return $this->getParameter('coupon');
    }

    public function setCoupon($value)
    {
        return $this->setParameter('coupon', $value);
    }

    public function getDefaultParameters()
    {
        return array(
            'account' => '',
            'secret' => '',
            'coupon' => '',
        );
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Btce\Message\CompletePurchaseRequest', $parameters);
    }

    public function refund(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Btce\Message\RefundRequest', $parameters);
    }
}
