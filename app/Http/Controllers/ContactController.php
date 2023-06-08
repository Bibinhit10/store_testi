<?php

// Bibinhit_10 ***

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\AboutUs;
use App\Models\ContactUsContact;
use File;

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

    public function add_CUC(Request $request)
    {

        $content_data=$request->validate([
            'instagram'=>['string','required'],
            'email'=>['string','required'],
            'phone_number'=>['string','required'],
            'address'=>['string','required'],
        ]);
        
        $content_data['id']=1;

        $s=ContactUsContact::find(1);


        if (empty($s)) {

            $data=ContactUsContact::create($content_data);
        
        }else {
            $data=ContactUsContact::where('id',1)->update($content_data);
        }



        return response()->json($data, 200);
    
    }

    public function get_CUC()
    {
        
        $C_data=ContactUsContact::find(1);

        return response()->json($C_data, 200);

    }

    public function add_about_us(Request $request)
    {

        $content_data=$request->validate([
            'text'=>['string','required'],
            'image'=>['image','required'],
        ]);
        
        $content_data['id']=1;

        $image_name=time().'.'.$content_data['image']->extension();

        $content_data['image']->move(public_path('images/about_us'),$image_name);    

        $content_data['image']=$image_name;


        $data=AboutUs::find(1);


        if (empty($data)) {

            AboutUs::create($content_data);
        
        }else {

            $image_path = public_path('images/about_us/'.$data['image']);

            if(File::exists($image_path)) {
                File::delete($image_path);
            }

            AboutUs::where('id',1)->update($content_data);
        }



        return response()->json($content_data, 200);
        
    }

    public function get_about()
    {
        $about_data=AboutUs::find(1);

        $about_data['image']='images/about_us/'.$about_data['image'];

        return response()->json($about_data, 200);

    }

}
