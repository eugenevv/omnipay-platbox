<?php

namespace Omnipay\PlatBox;

use Omnipay\Common\AbstractGateway;

/**
 * Gateway Class
 * https://platbox.com/new/docs/paybox_api_1.pdf
 */
class Gateway extends AbstractGateway
{
  public function getName()
  {
    return 'PlatBox';
  }

  public function getDefaultParameters()
  {
    return array(
      'merchant_id' => '',
      'account'     => '',
      'payer'       => '',
      'amount'      => '',
      'currency'    => '',
      'order'       => '',
      'project'     => '',
      'additional'  => '',
      'testMode'    => false,
    );
  }

  public function getSecretKey()
  {
    return $this->getParameter('secretKey');
  }

  public function setSecretKey($value)
  {
    return $this->setParameter('secretKey', $value);
  }

  public function getPassword()
  {
    return $this->getParameter('password');
  }

  public function setPassword($value)
  {
    return $this->setParameter('password', $value);
  }

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

  public function getAdditional()
  {
    return $this->getParameter('additional');
  }

  public function setAdditional($value)
  {
    return $this->setParameter('additional', $value);
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

  public function getCode()
  {
    return $this->getParameter('code');
  }

  public function setCode($value = '')
  {
    return $this->setParameter('code', $value);
  }

  public function getDescription()
  {
    return $this->getParameter('description');
  }

  public function setDescription($value = '')
  {
    return $this->setParameter('description', $value);
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

  /** In kopeks */
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

  /**
   * @param array $parameters
   *
   * @return \Omnipay\Common\Message\RequestInterface
   */
  public function purchase(array $parameters = array())
  {
    return $this->createRequest('\Omnipay\PlatBox\Message\InitPaymentRequest', $parameters);
  }

  /**
   * @param array $parameters
   *
   * @return \Omnipay\Common\Message\RequestInterface
   */
  public function completePurchase(array $parameters = array())
  {
    return $this->createRequest('\Omnipay\PlatBox\Message\CompletePurchaseRequest', $parameters);
  }

  /**
   * @param array $parameters
   *
   * @return \Omnipay\Common\Message\RequestInterface
   */
  public function pay(array $parameters = array())
  {
    return $this->createRequest('\Omnipay\PlatBox\Message\PayRequest', $parameters);
  }

  /**
   * @param array $parameters
   *
   * @return \Omnipay\Common\Message\RequestInterface
   */
  public function cancel(array $parameters = array())
  {
    return $this->createRequest('\Omnipay\PlatBox\Message\CancelRequest', $parameters);
  }
}
