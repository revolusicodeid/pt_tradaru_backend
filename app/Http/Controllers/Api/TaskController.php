<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\HelperServices\ArrayHelper;
use App\HelperServices\ProfitHelper;
use App\HelperServices\ShiftHelper;
use Validator;
use Exception;

class TaskController extends Controller
{
    public function __construct(
        ArrayHelper $array_helper, 
        ProfitHelper $profit_helper,
        ShiftHelper $shift_helper
    )
    {
        $this->array_helper = $array_helper;
        $this->profit_helper = $profit_helper;
        $this->shift_helper = $shift_helper;
    }
    //
    public function maxProfit(Request $request)
    {
        # code...
        try{
            
            $input = $request->all();
            $validator = Validator::make($input, [
                'arr' => 'required',
                'inp' => 'required',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat kesalahan pada sistem internal.',
                    'error'   => $validator->errors()
                ], 200);
            }

            $data = [
                "res" => [],
                "profit" => 0,
                "arr" => $this->array_helper->convertStrToArr($request->arr)
            ];

            for($i=0 ; $i < (int) $request->inp; $i++){
                if(!$this->array_helper->validateArr($data["arr"])){
                    return response()->json([
                        'status' => false,
                        'message' => 'cek input anda.',
                        'error'   => 'maximum input anda tidak boleh lebih dari 3.'
                    ], 200);
                }
                $p_data = $this->profit_helper->getMaxProfit($data["arr"]);
                $data["res"] = $this->array_helper->pushArr($data["res"],$p_data["res"]);
                $data["profit"] += $p_data["profit"];
                $data["arr"] = $p_data["arr"];
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil proses data.',
                "data" => $data
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Terdapat kesalahan pada sistem internal.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    
    public function shiftArray(Request $request)
    {
        # code...
        try{
            
            $input = $request->all();
            $validator = Validator::make($input, [
                'arr' => 'required',
                'inp' => 'required',
            ]);
            
            if($validator->fails()){
                return response()->json([
                    'status' => false,
                    'message' => 'Terdapat kesalahan pada sistem internal.',
                    'error'   => $validator->errors()
                ], 301);
            }

            $data = $this->array_helper->convertStrToArr($request->arr);

            if(!$this->array_helper->validateArrSquare($data)){
                return response()->json([
                    'status' => false,
                    'message' => 'struktur array anda salah.',
                    'error'   => 'struktur array harus cocok berupa persegi.'
                ], 301);
            }
            for($i=0;$i<(int) $request->inp;$i++){
                $data = $this->shift_helper->getShiftArray($data,(int) $request->inp);
            }

            return response()->json([
                'status' => true,
                'message' => 'Berhasil proses data.',
                "data" => $data
            ], 200);
        }catch(Exception $e){
            return response()->json([
                'status' => false,
                'message' => 'Terdapat kesalahan pada sistem internal.',
                'error'   => $e->getMessage()
            ], 500);
        }
    }

    
}
