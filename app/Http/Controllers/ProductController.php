<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;
use App\Models\Product;
use File;


class ProductController extends Controller
{
    
    public function add_product(Request $request)
    {

        $data_product=$request->validate([
            'title'=>['string','required'],
            'description'=>['string','required'],
            'features'=>['string','required'],
            'image_1'=>['image','required'],
            'image_2'=>['image'],
            'image_3'=>['image'],
            'subcategory_id'=>['string','required'],
            'home_page'=>['boolean'],
            'stock'=>['integer','required'],
            'price'=>['integer','required'],
            'discount_percent'=>['integer'],
        ]);

        $category=Categorie::where('id',$data_product['subcategory_id'])->first();
        
        if ( empty($category) || $category['parent_id']==null ) {
            return response()->json(' category id vojod nadarad ..! ', 500);
        }
        
        if ( isset($data_product['discount_percent']) ) {

            $data_product['after_discount']=$data_product['price'] - ($data_product['price'] * $data_product['discount_percent'] / 100);

        }else {

            $data_product['discount_percent']=0;
            $data_product['after_discount']=$data_product['price'];

        }
        
        $image_name='image'.time().'.'.$data_product['image_1']->extension();

        $data_product['image_1']->move(public_path('images/products/'),$image_name);    

        $data_product['image_1']=$image_name;

        if ( isset($data_product['image_2']) ) {
            
            $image_name='image'.time().'.'.$data_product['image_2']->extension();

            $data_product['image_2']->move(public_path('images/products/'),$image_name);    

            $data_product['image_2']=$image_name;

            if ( isset($data_product['image_3']) ) {
                
                $image_name='image'.time().'.'.$data_product['image_3']->extension();

                $data_product['image_3']->move(public_path('images/products/'),$image_name);    

                $data_product['image_3']=$image_name;
                
            }

        }

        product::create($data_product);
        
        return response()->json($data_product, 200);

        
    }

    public function update_product(Request $request , String $id)
    {

        $data_product=$request->validate([
            'title'=>['string'],
            'description'=>['string'],
            'features'=>['string'],
            'image_1'=>['image'],
            'image_2'=>['image'],
            'image_3'=>['image'],
            'subcategory_id'=>['string'],
            'home_page'=>['boolean'],
            'stock'=>['integer'],
            'price'=>['integer'],
            'discount_percent'=>['integer'],
        ]);

        if ( isset($data_product['subcategory_id']) ) {

            $category=Categorie::where('id',$data_product['subcategory_id'])->first();
            
            if ( empty($category) || $category['parent_id']==null ) {
                return response()->json(' category id vojod nadarad ..! ', 500);
            }
            
        }

        if ( isset($data_product['discount_percent']) ) {

            $data_product['after_discount']=$data_product['price'] - ($data_product['price'] * $data_product['discount_percent'] / 100);

        }
        
        if ( isset($data_product['image_1']) ) {
            
            $image_name='image'.time().'.'.$data_product['image_1']->extension();

            $data_product['image_1']->move(public_path('images/products/'),$image_name);    

            $data_product['image_1']=$image_name;


            $data=product::where('id',$id)->first();

            if (!empty($data)) {

                $image_path = public_path('images/products/'.$data['image_1']);

                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }
        

        }

        if ( isset($data_product['image_2']) ) {
            
            $image_name='image'.time().'.'.$data_product['image_2']->extension();

            $data_product['image_2']->move(public_path('images/products/'),$image_name);    

            $data_product['image_2']=$image_name;

            if (!empty($data)) {

                $image_path = public_path('images/products/'.$data['image_2']);

                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
            }

            if ( isset($data_product['image_3']) ) {
                
                $image_name='image'.time().'.'.$data_product['image_3']->extension();

                $data_product['image_3']->move(public_path('images/products/'),$image_name);    

                $data_product['image_3']=$image_name;
                
                if (!empty($data)) {

                    $image_path = public_path('images/products/'.$data['image_3']);

                    if(File::exists($image_path)) {
                        File::delete($image_path);
                    }
                }

            }

        }

        product::where('id',$id)->update($data_product);
        
        return response()->json($data_product, 200);

        
    }

    public function delete_product(String $id)
    {
        

        $data=product::where('id',$id)->first();

        if (!empty($data)) {

            $image_path = public_path('images/products/'.$data['image_1']);

            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            if (!empty($data['image_2'])) {

                $image_path = public_path('images/products/'.$data['image_2']);
    
                if(File::exists($image_path)) {
                    File::delete($image_path);
                }
    
            }

            if (!empty($data['image_3'])) {
                
                $image_path = public_path('images/products/'.$data['image_3']);

                if(File::exists($image_path)) {
                    File::delete($image_path);
                }

            }


        }

        product::where('id',$id)->delete();

    }

    public function get_products(Request $request)
    {

        $data=$request->validate([
            'search'=>['string'],
            'subcategory_id'=>['string'],
            'category_id'=>['string'],
            'order_by'=>['string'],
        ]);
        
        $eleqent = product::query();


        if( isset( $data['search'] ) ){

            $eleqent= $eleqent->where('title','LIKE',"%{$data['search']}%");

        }

        if( isset( $data['subcategory_id'] ) ){

            $eleqent= $eleqent->where('subcategory_id',$data['subcategory_id']);

        }
        
        if( isset( $data['category_id'] ) ){
            
            $P_id=$data['category_id'];
        
            $eleqent= $eleqent->whereHas('parent', function ($query) use($P_id) {

                $query->where('parent_id', $P_id );

            });

        }
        
        if( isset( $data['order_by'] ) ){

            if ( $data['order_by']=='most_sold' ) {
            
                $eleqent= $eleqent->orderBy('sold_count','DESC');

            }
            
            if ( $data['order_by']=='most_cheapest' ) {
                
                $eleqent= $eleqent->orderBy('after_discount','ASC');

            }

            if ( $data['order_by']=='most_expensive' ) {
                
                $eleqent= $eleqent->orderBy('after_discount','DESC');

            }

        }


        $a=$eleqent->get();


        return response()->json($a, 200);

    }
    
    public function get_by_id_product(String $id)
    {
        
        $data=product::where('id',$id)->with('SubCategorie','SubCategorie.parent')->get();

        return response()->json($data, 200);

    }

}
