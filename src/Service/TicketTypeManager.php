<?php 
namespace App\Service;

use App\Entity\Ticket;

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
        

        if($reservationDate->format('H')>14){
            $type= $type + 5;
        }
        return $type;
    }

    public function calculatePrice($type){//switch
        if($type==0 or $type==5){
            return 0;
        }//switch 
        if($type==1 OR $type==6){
            return 8;
        }
        if($type==2 or $type==7){
            return 15;
        }
        if($type==3 or $type==8){
            return 12;
        }
        if($type==4 or $type==9)
        {
            return 10;
        }
    }

    public function dayOrHalfDay($reservationDate)
    {
        if($reservationDate->format('H')>14){
            return "Demi-journée";
        } 
        return "Journée complète";
    }

    public function nameType($type)
    {//optimiser
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