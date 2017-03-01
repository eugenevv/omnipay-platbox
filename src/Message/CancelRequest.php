<?php

namespace Omnipay\PlatBox\Message;

/**
 * PlatBox cancel request
 */
class CancelRequest extends AbstractRequest
{
  /**
   * @param mixed $data
   *
   * @return CancelResponse
   */
  public function sendData($data)
  {
    return $this->response = new CancelResponse($this, $data);
  }
}
