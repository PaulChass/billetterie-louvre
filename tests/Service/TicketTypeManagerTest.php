<?php
/**
 * Created by PhpStorm.
 * User: paulc
 * Date: 28/01/2019
 * Time: 13:53
 */

 namespace App\Tests\Service;

 use App\Service\TicketTypeManager;
 use PHPUnit\Framework\TestCase;
 use Symfony\Component\Validator\Constraints\DateTime;

 class TicketTypeTest extends TestCase
 {
    public function testGuessPrice(){
       $ticketTypeManager= new TicketTypeManager();
       $result = $ticketTypeManager->guessPrice(0);
       $this->assertEquals(0,$result);        
    }

    public function testCalculateType(){
        $ticketTypeManager= new TicketTypeManager();
        $result = $ticketTypeManager->CalculateType(0,new \DateTime('2000-10-11'), new \DateTime());
        $this->assertEquals(2,$result);
    }
 }