<?php

namespace App\HelperServices;

class ArrayHelper 
{
    
    public function convertStrToArr($arr){
        return explode(", ",$arr);
    }
    
    public function getMax($arr){
        return (int) max($arr);
    }
    
    public function getMin($arr){
        return (int) min($arr);
    }

    public function delValArr($arr,$val){
        return array_diff($arr, (is_array($val) ? $val : array($val)));
    }

    public function delKeyArr($arr,$key){
        unset($arr[$key]);
        return $arr;
    }

    public function pushArr($arr,$addArr){
        array_push($arr,$addArr);
        return $arr;
    }

    public function validateArr($arr){
        return count($arr) > 1 ? true : false;
    }

    public function validateArrSquare($arr){
        return fmod(count($arr),(sqrt(count($arr)))) == 0 ? true : false;
    }

    public function squareArr($arr){
        return array_chunk($arr,sqrt(count($arr)));
    }


}