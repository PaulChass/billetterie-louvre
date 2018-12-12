<?php 
namespace App\Service;

class Calculator
{
    public function calculateType($checkbox,$birthday,$reservationDate)
    {
        $datetime2= new \Datetime();
        $diff = $birthday->diff($datetime2);
        $age = $diff->format('%Y');
        if($age<4){
            $type =0;
        }
        elseif($age<12){
            $type = 1;
        } 
        elseif($age<60){
            $type = 2;
        }    
        else{
            $type = 3;
        }


        if($checkbox==1){
            $type=4;
        }


        if($reservationDate->format('H')>14){
            $type= $type + 5;
        }
        return $type;
    }
    public function calculatePrice($type){
        if($type==0 or $type==5){
            return 0;
        }
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
}