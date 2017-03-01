<?php

namespace Omnipay\PlatBox\Message;

use Omnipay\Common\Message\AbstractResponse;
use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal Response
 */
class Response extends AbstractResponse
{
  public function __construct(RequestInterface $request, $data)
  {
    $this->request = $request;
    if (is_string($data)) {
      $this->data = json_decode($data);
    }
    else {
      $this->data = $data;
    }
  }

  public function isSuccessful()
  {
    return false;
  }

  public function confirm()
  {
    $response = array();
    $response['status'] = 'ok';
    $response['merchant_tx_id'] = $this->getRequest()->getMerchantTxId();
    $response['merchant_tx_extra'] = $this->getRequest()->getMerchantTxExtra();
    $response['sign'] = $this->getRequest()->generateSignature($response);

    ksort($response);

    return SyncRequest::send($response, $this->getRequest());
  }
}
