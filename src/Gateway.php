<?php

namespace Omnipay\Mobikwik;

use Omnipay\Common\AbstractGateway;


class Gateway extends AbstractGateway
{
    public function getName()
    {
        return 'Mobikwik';
    }

    public function getDefaultParameters()
    {
        return array(
            'mid' => ''
        );
    }

    public function getMID()
    {
        return $this->getParameter('mid');
    }

    public function setMID($value)
    {
        return $this->setParameter('mid', $value);
    }

    public function getOrderId()
    {
        return $this->getParameter('OrderId');
    }

    public function setOrderId($value)
    {
        return $this->setParameter('OrderId', $value);
    }

    public function getEmail()
    {
        return $this->getParameter('email');
    }

    public function setEmail($value)
    {
        return $this->setParameter('email', $value);
    }

    public function getCell()
    {
        return $this->getParameter('cell');
    }

    public function setCell($value)
    {
        return $this->setParameter('cell', $value);
    }

    public function getAmount()
    {
        return $this->getParameter('amount');
    }

    public function setAmount($value)
    {
        return $this->setParameter('amount', $value);
    }

    public function getRedirectUrl()
    {
        return $this->getParameter('redirecturl');
    }

    public function setRedirectUrl($value)
    {
        return $this->setParameter('redirecturl', $value);
    }

    public function purchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mobikwik\Message\PurchaseRequest', $parameters);
    }

    public function completePurchase(array $parameters = array())
    {
        return $this->createRequest('\Omnipay\Mobikwik\Message\CompletePurchaseRequest', $parameters);
    }
}
