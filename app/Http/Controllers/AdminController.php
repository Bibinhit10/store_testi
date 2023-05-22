<?php

// Bibinhit_10 ***

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
use App\Models\Categorie;
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

}


