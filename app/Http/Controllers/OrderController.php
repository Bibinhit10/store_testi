<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Cart;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use App\Models\DiscountUser;
use App\Models\Discount;
use App\Models\UserAddress;

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

    // 


    public function add_to_cart(Request $request)
    {

        $data=$request->validate([
            'product_id'=>['string','required'],
            'count'=>['integer','required'],
            'action'=>['string','required'],
        ]);
        
        $data['user_id']=$request->user()->id;


        $P=Product::where('id',$data['product_id'])->first();

        if (empty($P)) {
            
            return response()->json(' product vojof nadarad . ',500);
            
        }     

        $cart=Cart::where('user_id',$data['user_id'])->where('product_id',$data['product_id'])->first();

        if(empty($cart)){

            $data['total_amount']=$data['count']*$P['after_discount'];


            Cart::create($data);

        }else {

            $data['total_amount']=($data['count']*$P['after_discount']);

                
            if ($data['action']=='increment') {
            
                Cart::where('user_id',$data['user_id'])->where('product_id',$data['product_id'])->update([
                    'count' => DB::raw("count+{$data['count']}"),
                    'total_amount' => DB::raw("total_amount+{$data['total_amount']}"),
                ]);
            
            }
            

            if ($data['action']=='decrement') {
            
                if ($data['count']==$cart['count']) {
                    
                    Cart::where('user_id',$data['user_id'])->where('product_id',$data['product_id'])->delete();

                }else{
                
                    $data['total_amount']=$cart['total_amount']-($data['count']*$P['after_discount']);

                    Cart::where('user_id',$data['user_id'])->where('product_id',$data['product_id'])->update([
                        'count' => DB::raw("count-{$data['count']}"),
                        'total_amount' => DB::raw("total_amount-{$data['total_amount']}"),
                    ]);
                
                }
            
            }
               
        }


        return response()->json(' ok .. ', 200);


    }


    public function del_product_cart_by_id(Request $request)
    {
        
        $product_id=$request->validate([
            'product_id'=>['string']
        ]);

        $user_id=$request->user()->id;

        if (!isset($product_id['product_id']) ) {
            Cart::where('user_id',$user_id)->delete();
        }else{
            Cart::where('user_id',$user_id)->where('product_id',$product_id)->delete();
        }
        
        return response()->json(' delete shod . ', 200);

    }

    public function get_my_carts(Request $request)
    {
        
        $user_id=$request->user()->id;

        $data=Cart::where('user_id',$user_id)->with('Product')->get();

        // get cart (relations product.subcategory.category, discount) => 13

        return response()->json($data, 200);
    }


    public function add_cart_to_order(Request $request)
    {

        $status_data=[
            'discount_id'=>false,
            'payment_type'=>false,
        ];

        $order_data=$request->validate([
            'payment_type'=>['string','required'],//wallet OR bank
            'discount_id'=>['string'],
            'addres_id'=>['string','required'],
        ]);

        $order_data['user_id']=$request->user()->id;

        $order_data['status']='sending';

        $cart_data=Cart::where('user_id',$order_data['user_id'])->get();

        $order_data['before_discount']=0;

        foreach ($cart_data as $value) {
            $order_data['before_discount']+=$value['total_amount'];
        }

        $order_data['total_amount']=$order_data['before_discount'];
        
        $addres=UserAddress::where('id',$order_data['addres_id'])->where('user_id',$order_data['user_id'])->first();




        if ( empty($addres) ) {
            return response()->json(' addres true nist .! ', 500);
        }

        if( isset($order_data['discount_id'])) {
            
            $disc=DiscountUser::where('user_id',$order_data['user_id'])->where('discount_id',$order_data['discount_id'])->first();

            if ( empty($disc) ) {
                return response()->json(' discount vojod nadarad !. ', 500);
            }

            $disc=Discount::Where('id',$order_data['discount_id'])->first();

            if ($order_data['total_amount'] <= $disc['max_amount']) {

                $order_data['total_amount']=$order_data['before_discount'] - ($order_data['before_discount'] * $disc['discount_percent'] / 100);

                $status_data['discount_id']=true;
            }else {
                
                $order_data['total_amount']-=$disc['max_amount'];
                
                $status_data['discount_id']=true;
            }

        }

        
        if ($order_data['payment_type']=='wallet') {
            
            $user=User::where('id',$order_data['user_id'])->first();

            if ( $user['wallet_amount']==0) {
                return response()->json(' mojodi kafi nist . ', 500);
            }

            if ($order_data['total_amount']<=$user['wallet_amount']) {
                $status_data['payment_type']=true;
            } else {
                return response()->json(' mojodi kafi nist . ', 500);
            }

        }




        if ( $status_data['discount_id']) {
            
            DiscountUser::where('user_id',$order_data['user_id'])->delete();
            
            $disc=Discount::Where('id',$order_data['discount_id'])->first();

            if ( !empty($disc) ) {
                
            Discount::Where('id',$order_data['discount_id'])->delete();
            
            }

        }

        if ( $status_data['payment_type']) {
            
            User::where('id', $order_data['user_id'])->update([
                'wallet_amount' => DB::raw("wallet_amount-{$order_data['total_amount']}")
            ]);

        }


        $order_id=(Order::create($order_data))->id;


        foreach ($cart_data as $product) {
            $product['order_id']=$order_id;

            $a=[
            'id' => $product['id'],
            'product_id' => $product['product_id'],
            'count' => $product['count'],
            'total_amount' => $product['total_amount'],
            'created_at' => $product['created_at'],
            'updated_at'=> $product['updated_at'],
            'order_id'=> $product['order_id']
            ];
            
            OrderItem::create($a);
            
        }

        Cart::where('user_id',$order_data['user_id'])->delete();

        return response()->json($cart_data, 200);

        

    }




}
