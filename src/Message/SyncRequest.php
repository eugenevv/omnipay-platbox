<?php

namespace Omnipay\PlatBox\Message;

class SyncRequest
{
  public function __construct()
  {
  }

  /**
   * @param $data array
   * @param $request AbstractRequest
   *
   * @return mixed
   */
  public static function send($data, $request)
  {
    $headers = $request->getHeaders($data);

    foreach ($headers as $header) {
      header($header);
    }

    echo json_encode($data);

    return array(
      'headers' => $headers,
      'data'    => $data
    );
  }
}