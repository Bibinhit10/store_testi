<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ArticleResource;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\discount;
use App\Models\Article;
use App\Models\Wallet;


class AdminController extends Controller
{

    public function add()
    {

        $admin=[
            'name'=>'admin1',
            'password'=>'admin1'
        ];

        $admin['password'] = Hash::make($admin['password']);

        Admin::create($admin);


        return response()->json(' Ok . ',200);

    }

    public function login(Request $request)
    {

        $admin_data=$request->validate([
            'name'=>['required','string'],
            'password'=>['required','string']
        ]);

        $admin=Admin::where('name', $admin_data['name'])->first();

        if (empty($admin)) {
            return response()->json('name eshtebah ast ..!',500);
        };


        if (!Hash::check($admin_data['password'], $admin->password)) {
            return response()->json('name eshtebah ast ..!',500);
        }

        $token=[
            'token'=>$admin->createToken('auth_token')->plainTextToken
        ];

        return response()->json($token,200);


    }

    public function user_update(Request $request,string $id)
    {

        $user_data = $request->validate([
            'name' => ['string'],
            'password' => ['string'],
            'email'=> ['string'],
            'phone_number'=> ['integer'],
        ]);

        if (isset($user_data['password'])) {

            $user_data['password'] = Hash::make($user_data['password']);

        }

        $user = user::where('id', $id)->update($user_data);

        return response()->json([
            'message' => 'update shod !...'
        ], 200);

    }

    public function get_user_by_id(Request $request,String $id)
    {

        $user = user::where('id', $id)->first();


        if (empty($user))
        {
            return response()->json([
                'message' => ' user vojod nadarad !...'
            ], 200);
        }

        $user = new UserResource($user);

        return response()->json($user , 200);

    }

    public function get_users(Request $request)
    {

        $data= $request->validate([
            'search' => ['string'],
        ]);

        $eleqent = user::query();


        if( isset( $data['search'] ) ){

            $eleqent= $eleqent->where('name','LIKE',"%{$data['search']}%");

        }
         $data_users= $eleqent->get();

        if( empty( $data_users ) ){

            return response()->json( ' vojod nadarad ..! ', 500);

        }

        return response()->json( $data_users, 200);



    }
    
    public function get_user_addresses(Request $request)
    {
        
        $user_id= $request->validate([
            'user_id' => ['string'],
        ]);


        $eleqent = User::query();

        if( isset( $user_id['user_id'] ) ){

            $eleqent = $eleqent->where('id', $user_id);

        }
        
         $data_users= $eleqent->with('Addresses')->get();

         
        if( empty( $data_users ) ){

            return response()->json( ' vojod nadarad ..! ', 500);

        }

        return response()->json( $data_users, 200);



    }

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

    public function add_article(Request $request)
    {
        
        $data_article=$request->validate([
            'image'=>['required','image'],
            'title'=>['string','required'],
            'text'=>['string','required'],
            'is_important'=>['boolean'],        
        ]);
        
        $image_name=time().'.'.$data_article['image']->extension();

        $data_article['image']->move(public_path('images'),$image_name);    

        $data_article['image']=$image_name;

        Article::create($data_article);

        return response()->json($data_article, 200);
        

    }

    public function update_article(Request $request,String $id)
    {
        
        $data_article=$request->validate([
            'image'=>['image'],
            'title'=>['string'],
            'text'=>['string'],
            'is_important'=>['boolean'],            
        ]);

        if (isset($data_article['image'])) {

            $image_name=time().'.'.$data_article['image']->extension();
    
            $data_article['image']->move(public_path('images'),$image_name);    
    
            $data_article['image']=$image_name;
            
        }

        Article::where('id', $id)->update($data_article);     

        return response()->json(' ok ...! ', 200);

    }

    public function get_article_by_id(String $id)
    {
        
        $article=ArticleResource::collection(Article::where('id',$id)->get());

        return response()->json($article, 200);

    }

    public function get_articles(Request $request)
    {
        
        $important=$request->validate([
            'is_important'=>['boolean'],            
        ]);


        $eleqent = Article::query();

        if( isset( $important['is_important'] ) ){

            $eleqent= $eleqent->where('is_important',$important['is_important']);

        }



        $articles=$eleqent->get();

        return response()->json($articles, 200);

    }
    
}

