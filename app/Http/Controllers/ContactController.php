<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    
    public function add_contact_message(Request $request)
    {
        
        $message_data=$request->validate([
            'full_name'=>['string','required'],
            'phone_number'=>['string','required'],
            'email'=>['string','required'],
            'message'=>['string','required']
        ]);

        Contact::create($message_data);

        return response()->json($message_data, 200);

    }

    public function get_contact()
    {

        $massages=Contact::orderBy('is_seen','DESC')->orderBy('id', 'ASC')->get();

        return response()->json($massages, 200);
        
    }

    public function get_seen_contact_by_id(String $id)
    {

        $is_seen=['is_seen'=>1];

        $data=Contact::where('id',$id)->get();

        Contact::where('id',$id)->update($is_seen);

        return response()->json($data, 200);
        
    }


}
