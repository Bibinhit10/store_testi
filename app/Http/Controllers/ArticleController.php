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


class ArticleController extends Controller
{
    
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



        $articles=ArticleResource::collection($eleqent->get());

        return response()->json($articles, 200);

    }
    
}
