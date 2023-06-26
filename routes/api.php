<?php

// Bibinhit_10 ***

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\DiscountController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategorieController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// admin

    Route::post('admin/add', [AdminController::class,'add']);

    Route::post('admin/login', [AdminController::class,'login']);

    Route::group([
        'middleware' => ['auth:admin']
    ], function(){

        // A User
            Route::put('/admin/user_update/{id}',[UserController::class, 'user_update']);

            Route::get('/admin/user/{id}',[UserController::class, 'get_user_by_id']);

            Route::get('admin/users',[UserController::class,'get_users']);

            Route::get('admin/users/addresses',[UserController::class,'get_user_addresses']);
        // A User

        // A Wallet
            Route::post('admin/wallet/add',[WalletController::class,'add_wallet']);
            
            Route::get('admin/wallets',[WalletController::class,'get_wallet_history']);
        // A Wallet
        
        // A Discount
            Route::post('admin/discount/add',[DiscountController::class,'add_discount']);

            Route::get('admin/discounts',[DiscountController::class,'Get_discounts']);

            Route::delete('admin/discount/del',[DiscountController::class,'delete_discount']);

            Route::post('admin/discount/add_user',[DiscountController::class,'add_discount_to_user']);
        // A Discount
        
        // A Article
            Route::post('admin/article/add',[ArticleController::class,'add_article']);

            Route::put('admin/article/update/{id}',[ArticleController::class,'update_article']);

            Route::get('admin/article/{id}',[ArticleController::class,'get_article_by_id']);

            Route::get('admin/articles',[ArticleController::class,'get_articles']);
        // A Article

        // A Categorie
            Route::post('admin/categorie/add',[CategorieController::class,'add_categorie']);

            Route::put('admin/categorie/update/{id}',[CategorieController::class,'update_categore']);

            Route::get('admin/categories',[CategorieController::class,'get_categoreis']);
        // A Categorie

        // A Contacts
            Route::get('admin/contacts', [ContactController::class,'get_contact']);

            Route::get('admin/contact/{id}', [ContactController::class,'get_seen_contact_by_id']);
            
            Route::post('admin/contact_us_contact/add', [ContactController::class,'add_CUC']);
            
            Route::post('admin/about_us',[ContactController::class,'add_about_us']);

        // A Contacts

        // A Product

            Route::post('admin/product/add',[ProductController::class,'add_product']);

            Route::put('admin/product/update/{id}',[ProductController::class,'update_product']);

            Route::delete('admin/product/del/{id}',[ProductController::class,'delete_product']);

            Route::get('admin/product/{id}',[ProductController::class,'get_by_id_product']);

            Route::get('admin/products',[ProductController::class,'get_products']);

        // A Product


    });


// admin

// user

    Route::post('user/sign_up',[UserController::class, 'sign_up']);

    Route::post('user/login',[UserController::class,'login']);

    Route::group([
        'middleware' => ['auth:user']
    ], function(){

        Route::get('user/my_info',[UserController::class,'get_info']);

        Route::put('user/my_info/update',[UserController::class,'update_info']);
        
        // A Address
            Route::post('user/address/add',[UserController::class,'add_address']);

            Route::get('user/address',[UserController::class,'get_addresses']);

            Route::delete('user/address/del',[UserController::class,'delete_address']);

            Route::put('user/address/update',[UserController::class,'update_address']);
        // A Address

    });

// user

        // A Contacts

            Route::post('contact_us/add', [ContactController::class,'add_contact_message']);

            Route::get('contact_us_contact', [ContactController::class,'get_CUC']);

            Route::get('about_us', [ContactController::class,'get_about']);

        // A Contacts


    
