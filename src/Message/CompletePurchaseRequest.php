<?php

namespace Omnipay\PlatBox\Message;

use Omnipay\PlatBox\Message\PlatBoxException;

/**
 * PlatBox check request
 */
class CompletePurchaseRequest extends AbstractRequest
{
  /**
   * @param mixed $data
   *
   * @return CompletePurchaseResponse
   */
  public function sendData($data)
  {
    return $this->response = new CompletePurchaseResponse($this, $data);
  }
}
