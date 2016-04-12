<?php

namespace Omnipay\Mobikwik\Message;

use Omnipay\Common\Message\AbstractRequest;


class PurchaseRequest extends AbstractRequest
{

    protected $liveEndpoint = 'https://www.mobikwik.com/wallet';
    protected $testEndpoint = 'https://test.mobikwik.com/wallet';

    public function getData()
    {
        $data['cell'] = $this->getCell();
        $data['email'] = $this->getEmail();
        $data['amount'] = $this->getAmount();       
        $data['orderid'] = rand(10000, 9999999999);
        $data['redirecturl'] = $this->getRedirectUrl();
        $data['mid'] = $this->getMID();       
        $checkSumHash = $this->getChecksumHash($data);
        $data['checksum'] = $checkSumHash;
        return $data;
    }

    public function sendData($data)
    {
        return $this->response = new PurchaseResponse($this, $data);
    }

    public function getEndpoint()
    {
        return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
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

    public function getArray2Str(array $arrayList)
    {
        $result=array();
        foreach ($arrayList as $arr) {
            $result[]="'".$arr."'";
        }
        return implode("", is_array($result) ? $result : array());
    }

    private function getChecksumHash($data)
    {
        $calculateChecksum = $this->getArray2Str($data);
        $checksum = hash_hmac('sha256', $calculateChecksum, 'ju6tygh7u7tdg554k098ujd5468o');
        return $checksum;
    }
}
