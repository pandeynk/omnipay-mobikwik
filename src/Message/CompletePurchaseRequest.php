<?php

namespace Omnipay\Mobikwik\Message;

use Omnipay\Common\Exception\InvalidResponseException;

class CompletePurchaseRequest extends PurchaseRequest
{
    public function getData()
    {
        $parameters = $this->httpRequest->request->all();            
        return $parameters;   
    }

    public function sendData($data)
    {
        return $this->response = new CompletePurchaseResponse($this, $data);
    }

    public function verifyChecksum($parameters)
    {
        $statuscode = $parameters['statuscode'];
        $orderid = $parameters['orderid'];     //unique reference number created for each transaction at PayU’s end
        $amount = $parameters['amount'];
        $statusmessage = $parameters['statusmessage'];
        $mid = $parameters['mid'];
        $checksum = $parameters['checksum'];

        $value = "'" . $statuscode . "'";
        $value .= "'" . $orderid . "'";
        $value .= "'" . $amount . "'";
        $value .= "'" . $statusmessage . "'";
        $value .= "'" . $mid . "'";

        $checkstatus = "'" . $mid . "'";
        $checkstatus .= "'" . $orderid . "'";

        $checkStatusChecksum = hash_hmac('sha256', $checkstatus, 'ju6tygh7u7tdg554k098ujd5468o');

        $verifychecksum = hash_hmac('sha256', $value, 'ju6tygh7u7tdg554k098ujd5468o');

        if ($checksum == $verifychecksum) {
            $postfields = array(
                'mid' => $mid,
                'orderid' => $orderid,
                'checksum' => $checkStatusChecksum,
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://test.mobikwik.com/checkstatus');
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $result = curl_exec($ch);
            $xml = simplexml_load_string($result) or die("Error: Cannot create object");
            $orderid = $xml->orderid;
            $statuscode = $xml->statuscode;
            $refid = $xml->refid;
            $amount = $xml->amount;
            $statusmessage = $xml->statusmessage;
            $ordertype = $xml->ordertype;
            $checksum = $xml->checksum;

            $checkResponse = "'" . $statuscode . "'";
            $checkResponse .= "'" . $orderid . "'";
            $checkResponse .= "'" . $refid . "'";
            $checkResponse .= "'" . $amount . "'";
            $checkResponse .= "'" . $statusmessage . "'";
            $checkResponse .= "'" . $ordertype . "'";

            $checkResponseChecksum = hash_hmac('sha256', $checkResponse, 'ju6tygh7u7tdg554k098ujd5468o');
            $json = json_encode($xml);
            $resultData = json_decode($json, true);
            if($statuscode == 0 && $checksum == $checkResponseChecksum) {
                return $this->sendData($resultData); 
            } else {
                throw new InvalidResponseException($statusmessage);
            }
        } else {
            throw new InvalidResponseException($statusmessage);
        } 
        
    }
}
