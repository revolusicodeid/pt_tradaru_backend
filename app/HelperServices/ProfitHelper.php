<?php

namespace App\HelperServices;

use App\HelperServices\ArrayHelper;

class ProfitHelper 
{
    public function __construct()
    {
        $this->array_helper = new ArrayHelper;
    }

    public function getMaxProfit($arr){
        $max = $this->array_helper->getMax($arr);
        $arr = $this->array_helper->delValArr($arr,$max);
        $min = $this->array_helper->getMin($arr);
        $arr = $this->array_helper->delValArr($arr,$min);
        return [
            "res" => [
                "beli" => $min,
                "jual" => $max
            ],
            "profit" => ($max - $min),
            "arr" => $arr
        ];
    }
}