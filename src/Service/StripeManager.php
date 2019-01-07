<?php
namespace App\Service;


class StripeManager
{

    public function createCharge()
    {

        $this->getToken();
        $charge = \Stripe\Charge::create([
            'amount' => '1200',//$_POST['data-amount']?/
            'currency' => 'eur',
            'source' => $token ,
            'receipt_email' => 'jenny.rosen@example.com'
        ]);
        return $charge;
    }
    protected function getToken()
    {
        \Stripe\Stripe::setApiKey("sk_test_JLGZwXrHu1uScaSEf5nXYin3");//protected?
        $token = $_POST['stripeToken'];
    }
}
