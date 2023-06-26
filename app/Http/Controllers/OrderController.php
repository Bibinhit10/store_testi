<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    
    public function get_orders()
    {
        
        $data=Order::with('items')->get();

        return response()->json($data, 200);

    }

    public function get_order_by_id(String $id)
    {
        
        $order=Order::where('id',$id)->with('items')->first();

        return response()->json($order, 200);

    }

}
