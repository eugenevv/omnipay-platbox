<?php

namespace Omnipay\PlatBox\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal initPayment response
 */
class InitPaymentResponse extends Response
{
  public function __construct(RequestInterface $request, $data)
  {
    parent::__construct($request, $data);
  }

  public function isSuccessful()
  {
    return isset($this->data['platbox_id']);
  }
}
