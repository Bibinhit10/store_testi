<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\discount;
use App\Models\Categorie;
use App\Models\Article;
use App\Models\Wallet;

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
        
        $discount=discount::where('code',$data_discount['code'])->first();

        if (!empty($discount)) {
            
            $data_discount['code']= strtoupper(Str::random(4).'_'.rand(1,9).rand(1,9));

        }

        $discount=discount::create($data_discount);

    
        return response()->json($discount, 200);

    }

    public function Get_discounts()
    {
        
        // $discounts = discount::withTrashed()->get();
        
        $discounts = discount::get();

        return response()->json($discounts, 200);

    }

    public function delete_discount(Request $request)
    {
        
        $discount_id=$request->validate([
            'discount_id'=>['string','required'],
        ]);
        
        $data=discount::where('id', $discount_id)->delete();

        // $data=discount::withTrashed()->where('id', 1)->restore();

        return response()->json(' ok ... ', 200);
        

    }
    
}
