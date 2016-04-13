<?php

namespace Omnipay\Mobikwik;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    public function setUp()
    {
        parent::setUp();
        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());
        $this->options = array(
            'amount' => '10.00',
        );
    }
    
  	public function testPurchase()
    {
        $response = $this->gateway->purchase($this->options)->send();
        $this->assertFalse($response->isSuccessful());
        $this->assertTrue($response->isRedirect());
        $this->assertSame('POST', $response->getRedirectMethod());
        $data = $response->getRedirectData();
    }

    public function testCompletePurchase()
    {
        $this->getHttpRequest()->request->replace($this->getResponseStub());

        $response = $this->gateway->completePurchase($this->options)->send();
        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('0', $response->getStatusCode());
        $this->assertEquals('73852012', $response->getOrderId());
        $this->assertEquals('5.00', $response->getAmount());
        $this->assertEquals('The payment has been successfully collected', $response->getStatusMessage());
    }

    protected function getResponseStub()
    {
        return array(
            'statuscode' => '0',
            'orderid' => '73852012',
            'amount' => '5.00',
            'statusmessage' => 'The payment has been successfully collected',
            'mid' => '',
            'checksum' => 'bdd6e7240ffe0ee2b18083bf1582b8eb3152707e624cb70ef304122ae0cab684',
        );
    } 
}
