<?php

namespace Utils;

class PeriodConvert
{
    public function __construct()
    {
        // $with_freq = ['Daily', 'Weekly', 'Monthly', 'Twice A Month', 'Monthly', 'Every Two Month', 'Quarter Yearly', 'Biyearly', 'Yearly'];
        $this->tn_arr = ['Daily'=> 1, 'Weekly' => 7, 'Twice A Month'=> 15,  'Monthly' => 30,  'Every Two Month' => 60, 'Quarter Yearly' => 90, 'Biyearly'=> 180, 'Yearly'=> 365 ]; // tn = text and number array
    }

    public function convertFromTextToInt($text){
        $selected_item = null;
        foreach($this->tn_arr as $key => $val){
            if(strtoupper($key) === strtoupper($text)){
                $selected_item = $val;
            }
        }

        return $selected_item;
    }

    public function convertFromIntToText($num){
        $selected_item = array_search($num, $this->tn_arr); 

        return $selected_item;
    }
        
}




