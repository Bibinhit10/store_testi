<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\Categorie;

class CategorieController extends Controller
{
    
    public function add_categorie(Request $request)
    {
        
        $categorie_data=$request->validate([
            'title'=>['string','required'],            
            'parent_id'=>['integer'],            
        ]);

        $data=Categorie::create($categorie_data);

        return response()->json($data, 200);

    }

    public function update_categore(Request $request , String $id)
    {
        
        $title=$request->validate([
            'title'=>['string'],
        ]);
        
        $data = Categorie::where('id', $id)->update($title);

        return response()->json(' ok ... ', 200);


    }

    public function get_categorie_by_id(string $id)
    {

        $categorie=Categorie::where('id',$id)->with('parent')->with('SubCategorie')->get();

        return response()->json($categorie, 200);

    }

    public function get_categoreis(Request $request)
    {
        
        $search=$request->validate([
            'parent_id'=>['string'],
            'is_main'=>['boolean'],
        ]);

        $eleqent=Categorie::query();

        if ( isset($search['parent_id']) ){

            $eleqent->where('id',$search['parent_id']);

        }
        
        if ( isset($search['is_main']) ) {

            if ($search['is_main']==0) {
                $eleqent->with('SubCategorie');
            }else {
                $eleqent->with('parent');
            }

        }else {

            $eleqent->with('parent')->with('SubCategorie');

        }

        $categories=$eleqent->get();
        
        return response()->json($categories, 200);

    }

}
