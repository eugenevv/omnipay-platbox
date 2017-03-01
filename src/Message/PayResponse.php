<?php

namespace Omnipay\PlatBox\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal pay response
 */
class PayResponse extends Response
{
  public function __construct(RequestInterface $request, $data)
  {
    parent::__construct($request, $data);
  }

  public function isSuccessful()
  {
    return true;
  }

  public function confirm()
  {
    $response = array();
    $response['status'] = 'ok';
    $response['merchant_tx_timestamp'] = $this->getRequest()->getMerchantTxTimestamp();
    $response['merchant_tx_extra'] = $this->getRequest()->getMerchantTxExtra();
    $response['sign'] = $this->getRequest()->generateSignature($response);

    ksort($response);

    return SyncRequest::send($response, $this->getRequest());
  }
}
