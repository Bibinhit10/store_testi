<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\Wallet;


class WalletController extends Controller
{  
    
    public function add_wallet(Request $request)
    {

        $data=$request->validate([
            'user_id'=>['string','required'],
            'amount'=>['integer','required'],
            'action'=>['string','required'],
        ]);


        if ($data['action']=='increment') {
        
            Wallet::create($data);
            User::where('id', $data['user_id'])->update([
                'wallet_amount' => DB::raw("wallet_amount+{$data['amount']}")
            ]);

            return response()->json($data, 200);
        
        }
        

        if ($data['action']=='decrement') {
        
            Wallet::create($data);
            User::where('id', $data['user_id'])->update([
                'wallet_amount' => DB::raw("wallet_amount-{$data['amount']}")
            ]);

            return response()->json($data, 200);
        
        }
        
        return response()->json(['The information is not correct !..'], 500);


    }

    public function get_wallet_history(Request $request)
    {
        
        $search=$request->validate([
            'user_id'=>['string'],
            'to_created_at'=>['string'],
            'from_created_at'=>['string'],
        ]);
        

        $eleqent = Wallet::query();


        if( isset( $search['user_id'] ) ){

            $eleqent= $eleqent->where('user_id',$search['user_id']);

        }

        if( isset( $search['to_created_at'] ) && isset( $search['from_created_at'] )) {
            
            
            $search['to_created_at'] = jdate()->fromFormat('Y/m/d', $search['to_created_at'])->toCarbon()->toDateTimeString();
            $search['from_created_at'] = jdate()->fromFormat('Y/m/d', $search['from_created_at'])->toCarbon()->toDateTimeString();
            
            $eleqent= $eleqent->whereBetween('created_at' , [$search['from_created_at'] , $search['to_created_at']]);

            
        }

        if( isset( $search['to_created_at'] ) && !isset( $search['from_created_at'] ) ){

            $search['to_created_at'] = jdate()->fromFormat('Y/m/d', $search['to_created_at'])->toCarbon()->toDateTimeString();

            $eleqent= $eleqent->where('created_at','<', $search['to_created_at']);

        }

        if( isset( $search['from_created_at'] ) && !isset( $search['to_created_at'] ) ){

            $search['from_created_at'] = jdate()->fromFormat('Y/m/d', $search['from_created_at'])->toCarbon()->toDateTimeString();

            $eleqent= $eleqent->where('created_at','>', $search['from_created_at']);

        }


        $data_users= $eleqent->get();

        if( empty( $data_users ) ){

            return response()->json( ' vojod nadarad ..! ', 500);

        }


        return response()->json( $data_users, 200);
         
    }


}
