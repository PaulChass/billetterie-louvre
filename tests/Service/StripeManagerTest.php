<?php


 namespace App\Tests\Service;

 use App\Service\StripeManager;
 use PHPUnit\Framework\TestCase;
 use App\Service\TicketTypeManager;
 use App\Repository\TicketRepository;



 class StripeManagerTest extends TestCase
 {
    public function testStripePayment(){
        $stripeManager = new stripeManager();
        $result = $stripeManager-> stripePayment(42,new TicketTypeManager(),new TicketRepository());
        $this->assertEquals(true, $result);
    }
 }
 