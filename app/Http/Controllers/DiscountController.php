<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Discount;
use App\Models\DiscountUser;

class DiscountController extends Controller
{
 
    public function add_discount(Request $request)
    {

        $code = strtoupper(Str::random(4).'_'.rand(1,9).rand(1,9));

        $data_discount=$request->validate([
            'discount_percent'=>['required'],
            'max_amount'=>['integer'],
            'expiresat'=>['string','required'],
        ]);

        $data_discount['expiresat'] = jdate()->fromFormat('Y/m/d', $data_discount['expiresat'])->toCarbon()->toDateTimeString();

        $data_discount['code']=$code;
        
        $discount=Discount::where('code',$data_discount['code'])->first();

        if (!empty($discount)) {
            
            $data_discount['code']= strtoupper(Str::random(4).'_'.rand(1,9).rand(1,9));

        }

        $discount=Discount::create($data_discount);

    
        return response()->json($discount, 200);

    }

    public function Get_discounts()
    {
        
        // $discounts = Discount::withTrashed()->get();
        
        $discounts = Discount::get();

        return response()->json($discounts, 200);

    }

    public function delete_discount(Request $request)
    {
        
        $discount_id=$request->validate([
            'discount_id'=>['string','required'],
        ]);
        
        $data=Discount::where('id', $discount_id)->delete();

        // $data=Discount::withTrashed()->where('id', 1)->restore();
        DiscountUser::where('discount_id', $discount_id)->delete();

        return response()->json(' ok ... ', 200);
        

    }

    public function add_discount_to_user(Request $request)
    {
        
        $ides=$request->validate([
            'user_id'=>['string','required'],
            'discount_id'=>['string','required']
        ]);

        DiscountUser::create($ides);

        return response()->json(' ok .. ', 200);

    }
    
}
