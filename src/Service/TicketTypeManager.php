<?php 
namespace App\Service;

use App\Entity\Ticket;
use App\Repository\TicketRepository;

class TicketTypeManager
{
    public function calculateType($checkbox,$birthday,$reservationDate)
    {
        $datetime2= new \Datetime();
        $diff = $birthday->diff($datetime2);
        $age = $diff->format('%Y');
        if($checkbox==0){    
            switch($age) {
                case  ($age<4)  :
                    $type = 0;
                    break;
                case ($age < 12 &&  $age >=4 ) :
                    $type = Ticket::CHILD;
                    break;  
                case(12 <= $age && $age < 60):
                    $type = Ticket::NORMAL;
                    break;
                default :
                    $type = Ticket::SENIOR;
                    break;
            }
        }
        else{$type = Ticket::REDUCED;}
        

        if($reservationDate->format('H')>=14 )
        {
            $type= $type + 5;
        }
        return $type;
    }

    public function guessPrice($type)
    {
        switch($type)
        {
            case 0  :
                return 0;
                break;
            case 1  :
                return 8;
                break;
            case 2 :
                return 16;
                break ;
            case 3  :
                return 12;
                break;
            case 4 :
                return 10;
                break;
            case 5  :
                return 0;
                break;
            case 6  :
                return 4;
                break;
            case 7 :
                return 8;
                break ;
            case 8  :
                return 6;
                break;
            case 9 :
                return 5;
                break;
        }
    }

    public function calculatePrice($reservation, TicketRepository $ticketRepository){
        $totalPrice=0;
        $tickets = $ticketRepository->findByReservation($reservation);
        foreach($tickets as $ticket) {
            $totalPrice=$totalPrice + $ticket->getPrice();
        }
        return $totalPrice;

    }

    public function dayOrHalfDay($reservationDate)
    {
        if($reservationDate->format('H')>14){
            return "Demi-journée";
        } 
        return "Journée complète";
    }



    public function nameType($type)
    {
        switch($type){
            case 0  :
                return 'Aucun';
                break;
            case 1  :
                return 'Enfant';
                break;
            case 2 :
                return 'Normal';
                break ;
            case 3  :
                return "Senior";
                break;
            case 4 :
                return 'Réduit';
                break;
            case 5  :
                return 'Aucun';
                break;
            case 6  :
                return 'Enfant';
                break;
            case 7 :
                return 'Normal';
                break ;
            case 8  :
                return "Senior";
                break;
            case 9 :
                return 'Réduit';
                break;
        }
    }
}