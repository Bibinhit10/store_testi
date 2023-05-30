<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\UserAddressResource;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;
use App\Models\UserAddress;
use App\Models\Categorie;
use App\Models\Wallet;



class UserController extends Controller
{

// User

    public function sign_up(Request $request)
    {

        $user_data=$request->validate([
            'name' => ['required','string'],
            'password' => ['required','string'],
            'email' => ['required','string'],
            'phone_number'=> ['required','string'],
        ]);


        $user_data['password'] = Hash::make($user_data['password']);

        User::create($user_data);

        return response()->json(' Ok . ',200);

    }

    public function login(Request $request)
    {

        $user_data=$request->validate([
            'phone_number'=> ['required','string'],
            'password' => ['required','string'],
        ]);


        $user=User::where('phone_number', $user_data['phone_number'])->first();

        if (empty($user)) {
            return response()->json('phone_number ya password eshtebah ast ..!',500);
        };


        if (!Hash::check($user_data['password'], $user->password)) {
            return response()->json('phone_number ya password eshtebah ast ..!',500);
        }

        $token=[
            'token'=>$user->createToken('auth_token')->plainTextToken
        ];

        return response()->json($token,200);

    }

    public function get_info(Request $request)
    {
        
        $data= new UserResource($request->user());

        return response()->json($data,200);

    }

    public function update_info(Request $request)
    {

        $user=$request->user();

        $update_data=$request->validate([
            'name'=> ['string'],
            'password' => ['string'],
            'email'=> ['string'],
            'phone_number'=> ['string'],
        ]);
        

        if (isset($update_data['password'])) {

            $update_data['password'] = Hash::make($update_data['password']);

        }


        user::where('id', $user->id)->update($update_data);

        return response()->json([
            'message' => 'update shod !...'
        ], 200);


    }

    public function add_address(Request $request)
    {

        $address=$request->validate([
            'description'=> ['string','required'],
            'province' => ['string','required'],
            'city'=> ['string','required'],
        ]);

        $address['user_id']=$request->user()->id;


        UserAddress::create($address);
       

        return response()->json($address, 200);

    }

    public function get_addresses(Request $request)
    {
        
        $user_id=$request->user()->id;

        $addressess=UserAddressResource::collection(UserAddress::where('user_id', $user_id)->get());

        return response()->json($addressess, 200);

    }

    public function delete_address(Request $request)
    {

        $user_id=$request->user()->id;

        $id=$request->validate([
            'address_id'=> ['required']
        ]);
                
        $user_address=UserAddress::where('user_id',$user_id)->where('id',$id)->delete();

        return response()->json(' ok ... ', 200);
        
    }

    public function update_address(Request $request)
    {
        
        $user_id=$request->user()->id;

        $id=$request->validate([
            'address_id'=> ['required']
        ]);
        $new_address=$request->validate([
            'description'=> ['string'],
            'province' => ['string'],
            'city'=> ['string'],
        ]);
        
        $user_address=UserAddress::where('user_id',$user_id)->where('id',$id)->update($new_address);

        return response()->json([
            'message' => 'update shod !...'
        ], 200);


    }

// Admin
    
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



}
