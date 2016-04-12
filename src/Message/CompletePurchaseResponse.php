<?php

namespace Omnipay\Mobikwik\Message;

use Omnipay\Common\Message\AbstractResponse;

class CompletePurchaseResponse extends AbstractResponse
{

    public function isSuccessful()
    {
        return $this->getStatusCode() == '0';
    }

    public function getStatusCode()
    {
        return $this->data['statuscode'];
    }

    public function getOrderID()
    {
        return $this->data['orderid'];
    }

    public function getRefid()
    {
        return $this->data['refid'];
    }

    public function getAmount()
    {
        return $this->data['amount'];
    }

    public function getStatusMessage()
    {
        return $this->data['statusmessage'];
    }

    public function getOrderType()
    {
        return $this->data['ordertype'];
    }

    public function getChecksum()
    {
        return $this->data['checksum'];
    }

}
