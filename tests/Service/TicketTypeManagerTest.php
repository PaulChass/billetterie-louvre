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

 class TicketTypeTest extends TestCase
 {
    public function testGuessPrice(){
       $ticketTypeManager= new TicketTypeManager();
       $result = $ticketTypeManager->guessPrice(0);
       $this->assertEquals(0,$result);
    }

    public function testCalculateType(){
        $ticketTypeManager= new TicketTypeManager();
        $result = $ticketTypeManager->CalculateType(0,new \DateTime('2000-10-11' ), new \DateTime('2019-02-11 10:00:00'));
        $this->assertEquals(2,$result);
    }

    public function testNameType(){
        $ticketTypeManager= new TicketTypeManager();
        $result = $ticketTypeManager->nameType(2);
        $this->assertEquals('Normal',$result);

 }


 }