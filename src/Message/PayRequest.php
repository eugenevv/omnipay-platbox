<?php

namespace Omnipay\PlatBox\Message;

/**
 * PlatBox pay request
 */
class PayRequest extends AbstractRequest
{
  /**
   * @param mixed $data
   *
   * @return PayResponse
   */
  public function sendData($data)
  {
    return $this->response = new PayResponse($this, $data);
  }
}
