<?php

namespace Omnipay\PlatBox\Message;

/**
 * PlatBox initPayment Request
 */
class InitPaymentRequest extends AbstractRequest
{
  public function getData()
  {
    $this->validate('merchant_id', 'account_id', 'phone_number', 'amount', 'currency', 'order', 'project');

    $data = array();
    $data['merchant_id'] = $this->getMerchantId();
    $data['account'] = json_encode(array('id' => $this->getAccountId()));
    $data['payer'] = json_encode(array('phone_number' => $this->getPhoneNumber()));
    $data['amount'] = $this->getAmount() . '';
    $data['currency'] = $this->getCurrency();
    $data['order'] = json_encode(
      array(
        'type'     => 'order_id',
        'order_id' => $this->getOrderId(),
      )
    );
    $data['project'] = $this->getProject();

    return $data;
  }
}
