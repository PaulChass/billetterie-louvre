<?php
namespace App\Service;


use App\Repository\TicketRepository;

class StripeManager
{
    public function stripePayment($reservation, TicketTypeManager $ticketTypeManager, TicketRepository $ticketRepository)
    {
        try {
            \Stripe\Stripe::setApiKey('sk_test_zHkVE8A6tIqTx8AmqNSnTo6d');

            $amount = $ticketTypeManager->calculatePrice($reservation, $ticketRepository);
            $email = $reservation->getEmailAddress();
            $customer = \Stripe\Customer::create([
                "description" => "Customer Id " . $reservation,
                "email" => $email,
                "source" => 'tok_mastercard' // obtained with Stripe.js
            ]);
            $customer->sources->create(["source" => 'tok_mastercard']);
            $charge = \Stripe\Charge::create([
                "amount" => $amount*100,
                "currency" => "eur",
                "source" => 'tok_mastercard', // obtained with Stripe.js
                "description" => $email
            ]);
            return $charge;
        } catch (\Stripe\Error\Card $e) {
             // Since it's a decline, \Stripe\Error\Card will be caught
             $body = $e->getJsonBody();
             $err = $body['error'];

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
   // Something elshappened, completely unrelated to Stripe
         }
     }
}