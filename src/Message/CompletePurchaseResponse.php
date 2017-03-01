<?php

namespace Omnipay\PlatBox\Message;

use Omnipay\Common\Message\RequestInterface;

/**
 * PayPal check response
 */
class CompletePurchaseResponse extends Response
{
  public function __construct(RequestInterface $request, $data)
  {
    parent::__construct($request, $data);
  }

  public function isSuccessful()
  {
    return true;
  }
}
