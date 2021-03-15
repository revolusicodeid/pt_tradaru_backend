<?php

namespace App\HelperServices;

use App\HelperServices\ArrayHelper;

class ShiftHelper 
{
    public function __construct()
    {
        $this->array_helper = new ArrayHelper;
    }

    public function getShiftArray($arr,$inp){
        $arr = $this->array_helper->squareArr($arr);
        $struct_arr = [];
        for($i=0;$i<count($arr);$i++){
            $struct_arr[] = $this->getStructArr($arr[$i]);
        }
        $struct_arr = $this->getStructArr($struct_arr);
        $arr = $this->shiftRole($struct_arr);
        return $arr;
    }

    public function getStructArr($arr){
        $head = array_slice($arr, 0, 1, true);
        $arr = $this->array_helper->delKeyArr($arr,0);
        $tail = array_slice($arr, -1, 1);
        $arr = $this->array_helper->delKeyArr($arr,count($arr));
        $body = array_slice($arr, 0, count($arr));
        return [
            "head"=>$head,
            "body"=>$body,
            "tail"=>$tail
        ];
    }

    public function shiftRole($arr){
        //last tail of first array
        $first_tail = $arr["head"][0]["tail"];
        //first head of last array
        $last_head = $arr["tail"][0]["head"];
        //result first value array
        $res_first_head = [];
        //result last value array
        $res_last_tail = [];
        //result array
        $res_arr = [];
        foreach($arr as $key => $val){
            switch ($key) {
                case "head" :   $res_arr = $this->headRole($val[0],$res_arr);
                    break;
                case "body" :   
                                $res = $this->bodyRole($val,$res_arr,$first_tail,$last_head);
                                $res_arr = $res['res_arr'];
                                $res_first_head = $res['res_first_head'];
                                $res_last_tail = $res['res_last_tail'];
                    break;
                case "tail" :   $res_arr = $this->tailRole($val[0],$res_arr);
                    break;
            }
        }
        $res_arr = array_merge($res_first_head,$res_arr);
        $res_arr = array_merge($res_arr,$res_last_tail);
        return $res_arr;
    }

    public function headRole($arr,$fill_arr){
        $fill_arr = array_merge($fill_arr,$arr["head"]);
        $fill_arr = array_merge($fill_arr,$arr["body"]);
        return $fill_arr;
    }

    public function bodyRole($arr,$fill_arr,$first_tail,$last_head){
        return count($arr) > 1 ? $this->bodyRolePlural($arr,$fill_arr,$first_tail,$last_head) : $this->bodyRoleSingle($arr[0],$fill_arr,$first_tail,$last_head);
    }

    public function bodyRoleSingle($arr,$fill_arr,$first_tail,$last_head){
        $res_first_head = $arr["head"];
        $fill_arr = array_merge($fill_arr,$last_head);
        $fill_arr = array_merge($fill_arr,$arr["body"]);
        $fill_arr = array_merge($fill_arr,$first_tail);
        $res_last_tail = $arr["tail"];

        return [
            "res_arr" => $fill_arr,
            "res_first_head" => $res_first_head,
            "res_last_tail" => $res_last_tail
        ];
    }

    public function bodyRolePlural($arr,$fill_arr,$first_tail,$last_head){
        $res_first_head = [];
        $res_last_tail = [];

        foreach($arr as $key => $val){
            switch ($key){
                case 0 :    
                            $res_first_head = $val["head"];
                            $fill_arr = array_merge($fill_arr,$arr[$key + 1]["head"]);
                            $fill_arr = array_merge($fill_arr,$val["body"]);
                            $fill_arr = array_merge($fill_arr,$first_tail);
                    break;
                case count($arr)-1 : 
                            $res_last_tail = $val["tail"];
                            $fill_arr = array_merge($fill_arr,$last_head);
                            $fill_arr = array_merge($fill_arr,$val["body"]);
                            $fill_arr = array_merge($fill_arr,$arr[$key - 1]["tail"]);
                            
                    break;
                default : 
                            $fill_arr = array_merge($fill_arr,$arr[$key + 1]["head"]);
                            $fill_arr = array_merge($fill_arr,$val["body"]);
                            $fill_arr = array_merge($fill_arr,$arr[$key - 1]["tail"]);
                    break;
            }
        }

        return [
            "res_arr" => $fill_arr,
            "res_first_head" => $res_first_head,
            "res_last_tail" => $res_last_tail
        ];
    }

    public function tailRole($arr,$fill_arr){
        $fill_arr = array_merge($fill_arr,$arr["body"]);
        $fill_arr = array_merge($fill_arr,$arr["tail"]);
        return $fill_arr;
    }
    
}