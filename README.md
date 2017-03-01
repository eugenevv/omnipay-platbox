# Omnipay: PlatBox

**PlatBox driver for the Omnipay PHP payment processing library**

[Omnipay](https://github.com/thephpleague/omnipay) is a framework agnostic, multi-gateway payment
processing library for PHP 5.3+. This package implements PayPal support for Omnipay.

## Installation

Omnipay is installed via [Composer](http://getcomposer.org/). To install, simply add it
to your `composer.json` file:

```json
{
    "require": {
        "eugenevv/omnipay-platbox": "*"
    }
}
```

And run composer to update your dependencies:

    $ curl -s http://getcomposer.org/installer | php
    $ php composer.phar update

## Basic Usage

The following gateways are provided by this package:

* PlatBox API (https://platbox.com/new/docs/paybox_api_1.pdf)

* Init

```php
    $this->gateway = Omnipay\Omnipay::create('PlatBox');
    $this->gateway->setMerchantId('MERCHANT_ID');
    $this->gateway->setSecretKey('SECRET_KEY');
    $this->gateway->setProject('PROJECT');
    $this->gateway->setTestMode(false);
```

* initPayment (from PlatBox API)

```php
    //From your DB
    $order = OrderModel::findByPk('ORDER_ID');
    
    $request = $this->gateway->purchase();
    
    $request->setAccountId('USER_ACCOUNT_ID');
    $request->setPhoneNumber($phone);   //format: XXXXXXXXXX
    $request->setAmount($amount); //amount in kopecks
    $request->setCurrency('RUB');
    $request->setOrderId($order->id);
    $response = $request->send();
    $result = $response->getData();
```

* callback "check" (from PlatBox API)

```php
    try {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);
      
      $request = $this->gateway->completePurchase($data);
      
      //From your DB
      $order = OrderModel::findByPk($request->getOrderId());
 
      $request->setMerchantTxId($order->id);
      $request->setMerchantTxExtra(
        json_encode(
          array(
            'type'     => 'order_id',
            'order_id' => $order->id,
          )
        )
      );
      $request->setMerchantAmount($order->amount);
      $request->setMerchantCurrency('RUB');
      $request->setExceptionMessage("[billing_payment_orders].id = {$order->id}");
      
      //some custom statuses
      switch ($order->status) {
        case 'STATUS_SUCCESS':
          $request->setExceptionCode(PlatBoxException::CODE_PAYMENT_ALREADY_COMPLETED);
          break;
        case 'STATUS_CANCELED':
          $request->setExceptionCode(PlatBoxException::CODE_PAYMENT_ALREADY_CANCELED);
          break;
        case 'STATUS_INVALID_AMOUNT':
          $request->setExceptionCode(PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT);
          break;
        default:
      }
 
      $response = $request->send();
      $result = $response->getData();
 
      $confirm = $response->confirm();
 
    } catch (PlatBoxException $e) {
      if ($e->getCode() == PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT && $order) {// Bad payment sum
        //... your code
      }
 
      $request->error($e->getMessage(), $e->getCode());
 
    } catch (Exception $e) {
 
      $request->error('Unknown error');
 
    }
```

* callback "pay" (from PlatBox API)

```php
    //Example transaction for Yii
    //$transaction = Yii::app()->db->beginTransaction();
    
    try {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);
    
      $request = $this->gateway->pay($data);
      
      //From your DB
      $order = OrderModel::findByPk($request->getOrderId());
 
      $request->setMerchantTxId($order->id);
      $request->setMerchantTxTimestamp($order->start_date);
      $request->setMerchantTxExtra(
        json_encode(
          array(
            'type'     => 'order_id',
            'order_id' => $order->id,
          )
        )
      );
      $request->setMerchantAmount($order->amount);
      $request->setMerchantCurrency('RUB');
      $request->setExceptionMessage("[billing_payment_orders].id = {$order->id}");
      
      //some custom statuses
      switch ($order->status) {
        case 'STATUS_SUCCESS':
          $request->setExceptionCode(PlatBoxException::CODE_PAYMENT_ALREADY_COMPLETED);
          break;
        case 'STATUS_CANCELED':
          $request->setExceptionCode(PlatBoxException::CODE_PAYMENT_ALREADY_CANCELED);
          break;
        case 'STATUS_INVALID_AMOUNT':
          $request->setExceptionCode(PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT);
          break;
        default:
      }
 
      $response = $request->send();
      $result = $response->getData();
 
      $confirm = $response->confirm();
 
      // ... your code for payment success ($user->addAmount($order->amount))
 
      //Example transaction for Yii
      //$transaction->commit();
 
    } catch (PlatBoxException $e) {
      if ($e->getCode() == PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT && $order) {// Bad payment sum
        // ... your code for bad payment sum
      }
 
      //Example transaction for Yii
      //$transaction->commit();
 
      $request->error($e->getMessage(), $e->getCode());
 
    } catch (Exception $e) {
 
      //$transaction->rollback();
 
      $request->error('Unknown error');
      
    }
```

* callback "cancel" (from PlatBox API)

```php
    try {
      $data = file_get_contents('php://input');
      $data = json_decode($data, true);    
    
      $request = $this->gateway->cancel($data);
      
      //From your DB
      $order = OrderModel::findByPk($request->getOrderId());
 
      $request->setMerchantTxId($order->id);
      $request->setMerchantTxTimestamp($order->start_date);
      $request->setMerchantTxExtra(
        json_encode(
          array(
            'type'     => 'order_id',
            'order_id' => $order->id,
          )
        )
      );
      $request->setMerchantAmount($order->amount);
      $request->setMerchantCurrency('RUB');
      $request->setExceptionMessage("[billing_payment_orders].id = {$order->id}");
      
      //some custom statuses
      switch ($order->status) {
        case 'STATUS_SUCCESS':
          $request->setExceptionCode(PlatBoxException::CODE_PAYMENT_ALREADY_COMPLETED);
          break;
        default:
      }
 
      $response = $request->send();
      $result = $response;
 
      if ($order) {
        // ... your code for cancel payment
      }
 
      $confirm = $response->confirm();
 
    } catch (PlatBoxException $e) {
      if ($e->getCode() == PlatBoxException::CODE_INVALID_PAYMENT_AMOUNT && $order) {// Bad payment sum
        // ... your code for bad payment sum
      }
 
      $request->error($e->getMessage(), $e->getCode());
      
    } catch (Exception $e) {
 
      $error = $request->error('Unknown error');
      
    }
```

For general usage instructions, please see the main [Omnipay](https://github.com/thephpleague/omnipay)
repository.

## Out Of Scope

Omnipay does not cover recurring payments or billing agreements, and so those features are not included in this package. Extensions to this gateway are always welcome. 

## Support

If you are having general issues with Omnipay, we suggest posting on
[Stack Overflow](http://stackoverflow.com/). Be sure to add the
[omnipay tag](http://stackoverflow.com/questions/tagged/omnipay) so it can be easily found.

If you want to keep up to date with release anouncements, discuss ideas for the project,
or ask more detailed questions, there is also a [mailing list](https://groups.google.com/forum/#!forum/omnipay) which
you can subscribe to.

If you believe you have found a bug, please report it using the [GitHub issue tracker](https://github.com/thephpleague/omnipay-paypal/issues),
or better yet, fork the library and submit a pull request.
