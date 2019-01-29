<?php


 namespace App\Tests\Service;

 use App\Service\StripeManager;
 use PHPUnit\Framework\TestCase;
 use App\Service\TicketTypeManager;
 use App\Repository\TicketRepository;
 use App\Entity\Ticket;

 class StripeManagerTest extends TestCase
 {
    public function testStripePayment(){
        $stripeManager = new stripeManager();
        $ticketTypeManager = new TicketTypeManager();
        $ticket = new Ticket();
        $this->$ticket->getRepository(Ticket::class);
        $result = $stripeManager-> stripePayment(42,$ticketTypeManager,$ticketRepository);
        $this->assertEquals(true, $result);
    }
 }
 