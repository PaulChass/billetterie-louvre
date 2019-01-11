<?php
namespace App\Service;


class StripeManager
{

    public function createCustomer()
    {
        $this->setKey();

        $customer = \Stripe\Customer::create([
            "description" => "Customer for jenny.rosen@example.com",
            "source" => "tok_mastercard" // obtained with Stripe.js
        ]);
        return $customer;
    }

    public function createCard($customer)
    {
        $this->setKey();
        $customer = \Stripe\Customer::retrieve($customer);
        $customer->sources->create(["source" => "tok_mastercard"]);
        return $customer;
    }

    public function createCharge()
    {
        $this->setkey();
        $token = $_POST['stripeToken'];
        $charge = \Stripe\Charge::create([
            'amount' => '1200',//$_POST['data-amount']?/
            'currency' => 'eur',
            'source' => $token,
            'receipt_email' => 'jenny.rosen@example.com'
        ]);
        return $charge;
    }

    protected function setKey()
    {
        \Stripe\Stripe::setApiKey("sk_test_JLGZwXrHu1uScaSEf5nXYin3");
    }
}
 /*try {
  // Use Stripe's library to make requests...
} catch(\Stripe\Error\Card $e) {
  // Since it's a decline, \Stripe\Error\Card will be caught
  $body = $e->getJsonBody();
  $err  = $body['error'];

  print('Status is:' . $e->getHttpStatus() . "\n");
  print('Type is:' . $err['type'] . "\n");
  print('Code is:' . $err['code'] . "\n");
  // param is '' in this case
  print('Param is:' . $err['param'] . "\n");
  print('Message is:' . $err['message'] . "\n");
} catch (\Stripe\Error\RateLimit $e) {
  // Too many requests made to the API too quickly
} catch (\Stripe\Error\InvalidRequest $e) {
  // Invalid parameters were supplied to Stripe's API
} catch (\Stripe\Error\Authentication $e) {
  // Authentication with Stripe's API failed
  // (maybe you changed API keys recently)
} catch (\Stripe\Error\ApiConnection $e) {
  // Network communication with Stripe failed
} catch (\Stripe\Error\Base $e) {
  // Display a very generic error to the user, and maybe send
  // yourself an email
} catch (Exception $e) {
  // Something else happened, completely unrelated to Stripe
}*/