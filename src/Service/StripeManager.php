<?php
namespace App\Service;


use App\Repository\TicketRepository;

class StripeManager
{
    public function stripePayment($reservation, TicketTypeManager $ticketTypeManager, TicketRepository $ticketRepository)
    {
        try {

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
            return $charge['paid'];
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
            log(1);
         } catch (\Stripe\Error\InvalidRequest $e) {
            log(1);
         } catch (\Stripe\Error\Authentication $e) {
            log(1);
         } catch (\Stripe\Error\ApiConnection $e) {
            log(1);
         } catch (\Stripe\Error\Base $e) {
            log(1);
         } catch (Exception $e) {
            log(1);
         }
     }
}