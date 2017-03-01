<?php
/**
 * PlatBox Abstract Request
 */

namespace Omnipay\PlatBox\Message;

use Omnipay\Common\Exception\InvalidRequestException;
use Omnipay\PlatBox\Message\PlatBoxException;

/**
 * PlatBox Abstract Request
 */
abstract class AbstractRequest extends \Omnipay\Common\Message\AbstractRequest
{
  const API_VERSION = '1.0';

  protected $liveEndpoint = 'https://paybox-global2.platbox.com/merchant';
//  protected $liveEndpoint = 'https://playground.platbox.com/merchant';

  protected $testEndpoint = 'https://playground.platbox.com/merchant';

  /** @var  resource cURL handle */
  protected $curl;

  protected $debugCurl = false;
  protected $debugCurlFile = null;

  public function getMerchantId()
  {
    return $this->getParameter('merchant_id');
  }

  public function setMerchantId($value)
  {
    return $this->setParameter('merchant_id', $value);
  }

  public function getAccount()
  {
    return $this->getParameter('account');
  }

  public function setAccount($value)
  {
    return $this->setParameter('account', $value);
  }

  public function getPayer()
  {
    return $this->getParameter('payer');
  }

  public function setPayer($value)
  {
    return $this->setParameter('payer', $value);
  }

  public function getAmount()
  {
    return $this->getParameter('amount');
  }

  public function setAmount($value)
  {
    return $this->setParameter('amount', $value);
  }

  public function getCurrency()
  {
    return $this->getParameter('currency');
  }

  public function setCurrency($value)
  {
    return $this->setParameter('currency', $value);
  }

  public function getOrder()
  {
    return $this->getParameter('order');
  }

  public function setOrder($value)
  {
    return $this->setParameter('order', $value);
  }

  public function getOrderId()
  {
    $order = $this->getParameter('order');

    if (is_array($order) && isset($order['order_id'])) {
      return $order['order_id'];
    }

    return null;
  }

  public function setOrderId($value)
  {
    $value = array(
      'order_id' => $value,
    );

    return $this->setOrder($value);
  }

  public function getPayment()
  {
    return $this->getParameter('payment');
  }

  public function setPayment($value)
  {
    return $this->setParameter('payment', $value);
  }

  public function getProject()
  {
    return $this->getParameter('project');
  }

  public function setProject($value)
  {
    return $this->setParameter('project', $value);
  }

  public function getProduct()
  {
    return $this->getParameter('product');
  }

  public function setProduct($value)
  {
    return $this->setParameter('product', $value);
  }

  public function getStatus()
  {
    return $this->getParameter('status');
  }

  public function setStatus($value)
  {
    return $this->setParameter('status', $value);
  }

  public function getMerchantTxId()
  {
    return $this->getParameter('merchant_tx_id');
  }

  public function setMerchantTxId($value)
  {
    return $this->setParameter('merchant_tx_id', $value);
  }

  public function getMerchantTxExtra()
  {
    return $this->getParameter('merchant_tx_extra');
  }

  public function setMerchantTxExtra($value)
  {
    return $this->setParameter('merchant_tx_extra', $value);
  }

  public function getCode()
  {
    return $this->getParameter('code');
  }

  public function setCode($value = '')
  {
    return $this->setParameter('code', $value);
  }

  public function getExceptionCode()
  {
    return $this->getParameter('exception_code');
  }

  public function setExceptionCode($value = '')
  {
    return $this->setParameter('exception_code', $value);
  }

  public function getExceptionMessage()
  {
    return $this->getParameter('exception_message');
  }

  public function setExceptionMessage($value = '')
  {
    return $this->setParameter('exception_message', $value);
  }

  public function getSecretKey()
  {
    return $this->getParameter('secretKey');
  }

  public function setSecretKey($value)
  {
    return $this->setParameter('secretKey', $value);
  }

  public function getMerchantTxTimestamp()
  {
    return $this->getParameter('merchant_tx_timestamp');
  }

  public function setMerchantTxTimestamp($value)
  {
    return $this->setParameter('merchant_tx_timestamp', $value);
  }

  public function getIsSuccessRequest()
  {
    return $this->getParameter('is_success_request');
  }

  public function setIsSuccessRequest($value = true)
  {
    return $this->setParameter('is_success_request', $value);
  }

  public function getMerchantOrderId()
  {
    return $this->getParameter('merchant_order_id');
  }

  public function setMerchantOrderId($value = '')
  {
    return $this->setParameter('merchant_order_id', $value);
  }

  public function getMerchantAmount()
  {
    return $this->getParameter('merchant_amount');
  }

  public function setMerchantAmount($value = '')
  {
    return $this->setParameter('merchant_amount', $value);
  }

  public function getMerchantCurrency()
  {
    return $this->getParameter('merchant_currency');
  }

  public function setMerchantCurrency($value = '')
  {
    return $this->setParameter('merchant_currency', $value);
  }

  public function getPlatboxTxId()
  {
    $platboxTxId = $this->getParameter('platbox_tx_id');

    if ($platboxTxId) {
      return $platboxTxId;
    }

    return '';
  }

  public function setPlatboxTxId($value = '')
  {
    return $this->setParameter('platbox_tx_id', $value);
  }

  public function getAccountId()
  {
    return $this->getParameter('account_id');
  }

  public function setAccountId($value = '')
  {
    return $this->setParameter('account_id', $value);
  }

  public function getPhoneNumber()
  {
    return $this->getParameter('phone_number');
  }

  public function setPhoneNumber($value = '')
  {
    return $this->setParameter('phone_number', $value);
  }

  public function generateSignature($data)
  {
    ksort($data);
    $dataJson = json_encode($data);

    return hash_hmac("SHA256", $dataJson, $this->getSecretKey());
  }

  public function sendData2($data)
  {
    $httpRequest = $this->httpClient->get($this->getEndPoint() . '?' . $this->getQuery($data), null);
//    $httpRequest->getCurlOptions()->set(CURLOPT_SSLVERSION, 6); // CURL_SSLVERSION_TLSv1_2 for libcurl < 7.35
    $signature = $this->generateSignature($data);
    $httpRequest->setHeader('X-Signature', $signature);
    $httpResponse = $httpRequest->send();

    return $this->createResponse($httpResponse->getBody());
  }

  public function sendData($data)
  {
    $curl = $this->buildCurlClient($data);
    $response = curl_exec($curl);
    curl_close($curl);

    if ($this->debugCurl) {
      fwrite($this->debugCurlFile, serialize($data));
      fclose($this->debugCurlFile);
    }

    return $this->createResponse($response);
  }

  /**
   * Build the cURL client.
   *
   * @return resource
   */
  public function buildCurlClient($data)
  {
    $this->curl = curl_init($this->getEndPoint() . '?' . $this->getQuery($data));

    $headers        = $this->getHeaders($data);

    // configuring cURL not to verify the server certificate
    curl_setopt($this->curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($this->curl, CURLOPT_SSL_VERIFYHOST, false);

    // setting the content type
    curl_setopt($this->curl, CURLOPT_HTTPHEADER, $headers);

    // telling cURL to return the HTTP response body as operation result
    // value when calling curl_exec
    curl_setopt($this->curl, CURLOPT_RETURNTRANSFER, true);

    if ($this->debugCurl) {
      curl_setopt($this->curl, CURLOPT_VERBOSE, true);
      $this->globalFileHandle = fopen('./curl.txt', 'w+');
      curl_setopt($this->curl, CURLOPT_STDERR, $this->debugCurlFile);
    }

    return $this->curl;
  }

  protected function getQuery($data)
  {
    $result = '';

    $queryData = $data;
    $queryData['sign'] = $this->generateSignature($queryData);

    foreach ($queryData as $k=>$v) {
      if (!empty($result) > 0) {
        $result .=  '&';
      }
      $result .=  $k . '=' . $v;
    }

//    $query = http_build_query($queryData);
//    echo $query;die;
//    $query = rawurlencode($query);

    return $result;
  }

  /**
   * Get the transaction headers.
   *
   * @return array
   */
  public function getHeaders($data)
  {
    $signature = $this->generateSignature($data);

    return array(
      'Content-Type: application/json',
      'X-Signature: ' . strtoupper($signature),
      'Cache-Control: no-cache',
    );
  }

  protected function getEndpoint()
  {
    return $this->getTestMode() ? $this->testEndpoint : $this->liveEndpoint;
  }

  protected function createResponse($data)
  {
    return $this->response = new Response($this, $data);
  }

  protected function throwExceptionByCode($code, $comment = '')
  {
    throw new PlatBoxException(PlatBoxException::getExceptionMessageByCode($code) . $comment, $code);
  }

  public function error($message = 'Unknown error', $code = 0)
  {
    $request = array();
    $request['status'] = 'error';
    $request['code'] = $code;
    $request['description'] = $message;
    $request['sign'] = $this->generateSignature($request);

    ksort($request);

    return SyncRequest::send($request, $this);
  }

  public function getData()
  {
    $data = $this->getParameters();

    $requestBodyLog =  'Все параметры (PlatBox + мерчант): ' . serialize($data);

    $order = $data['order'];
    if (!isset($order)) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_MESSAGE_FORMAT, '[order] не найден в запросе от PlatBox. ' . $requestBodyLog);
    }
    if (!isset($order['order_id'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_MESSAGE_FORMAT, '[order][order_id] не найден в запросе от PlatBox. ' . $requestBodyLog);
    }
    if (empty($order['order_id'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_DATA_REQUEST, '[order][order_id] пустой в запросе от PlatBox. ' . $requestBodyLog);
    }

    $merchantTxId = $this->getMerchantTxId();
    if ($merchantTxId != $order['order_id']) {
      $this->throwExceptionByCode(PlatBoxException::CODE_ORDER_NOT_FOUND_OR_BAD_DATA, "merchant_tx_id мерчанта: {$merchantTxId}, не равен PlatBox [order][order_id]: {$order['order_id']}. " . $requestBodyLog);
    }

    $payment = $data['payment'];
    if (!isset($payment)) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_MESSAGE_FORMAT, '[payment] не найден в запросе от PlatBox. ' . $requestBodyLog);
    }
    if (!isset($payment['amount'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_MESSAGE_FORMAT, '[payment][amount] не найден в запросе от PlatBox. ' . $requestBodyLog);
    }
    if (empty($payment['amount'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_DATA_REQUEST, '[payment][amount] пустой в запросе от PlatBox. ' . $requestBodyLog);
    }

    $merchantAmount = $this->getMerchantAmount();
    if (empty($merchantAmount) || !empty($merchantAmount) && $merchantAmount != $payment['amount']) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT, "Сумма пратежа мерчанта: {$merchantAmount}. Сумма платежа PlatBox: {$payment['amount']}. ". $requestBodyLog);
    }

    if (!isset($payment['currency'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_MESSAGE_FORMAT, '[payment][currency] не найден в запросе от PlatBox. ' . $requestBodyLog);
    }
    if (empty($payment['currency'])) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_DATA_REQUEST, '[payment][currency] пустой в запросе от PlatBox. ' . $requestBodyLog);
    }

    $merchantCurrency = $this->getMerchantCurrency();
    if (!empty($merchantCurrency) && $merchantCurrency != $payment['currency']) {
      $this->throwExceptionByCode(PlatBoxException::CODE_INVALID_PAYMENT_CURRENCY, $requestBodyLog);
    }

    $exceptionCode = $this->getExceptionCode();
    if ($exceptionCode) {
      $exceptionMessage = ($this->getExceptionMessage()) ? $this->getExceptionMessage() . '. ' : '';
      $this->throwExceptionByCode($exceptionCode, $exceptionMessage . $requestBodyLog);
    }

    return $data;
  }
}
