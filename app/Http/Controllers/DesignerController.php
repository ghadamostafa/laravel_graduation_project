<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Auth\RegisterController;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use \App\Design;
use \App\DesignerRate;
use \App\DesignImage;
use App\Profile;
use App\CompanyDesign;
use Auth;
use Illuminate\Support\Facades\DB;

class DesignerController extends Controller
{
    protected function create(array $data)
    {
        if(array_key_exists("image",$data))
            $image = $data['image']->store('uploads', 'public');
        else
            $image="images/default.jpg";

        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'address' => $data['address'],
            'phone' => $data['phone'],
            'image' => $image,
            'role' => 'designer',
            'password' => Hash::make($data['password']),
        ]);
    }
    

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $designers = User::whereHas('profile', function($query) {
            $query->where('is_verified','=','accepted');})->where('role','designer')->orderBy('likes', 'DESC')->paginate(10);
        return view('designer.index', compact('designers'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user = Auth::user();
        $vote_exist =  DesignerRate::where(['designer_id'=> $id,'liker_id'=>Auth::id()])->get();
        $about = Profile::where('user_id',$id)->get();
        $designer = User::where(['role'=>'designer','id'=>$id])->get();
        $current_designs = Design::where('designer_id', $id)->get()->count();
        $likes_count =User::findOrFail($id)->likes;
        $designs = Design::where('designer_id',$id)->get();
        // $cimage_array=[];
        // foreach($designs as $design)
        // {
        //     $design_image = DesignImage::where('design_id',$design->id)->get();
        // array_push($cimage_array, $design_image[0]); 
        // }
        $featured_designs = Design::where(['designer_id'=>$id,'featured'=>1])->get();
        $fimage_array=[];
        foreach($featured_designs as $design)
        {
            $featured_image = DesignImage::where('design_id',$design->id)->get();
        array_push($fimage_array, $featured_image[0]); 
        }
        $prev_works = Design::where(['designer_id'=>$id,'state'=>'sold'])->get();

        $prev_work_count = $prev_works->count();

        // if($prev_work_count > 0 )
        // {

        //     foreach($prev_works as $prev_work)
        //     {
        //         $prev_images = CompanyDesign::where('design_id',$prev_work->id)->get();
                
        //     }
        //     dd($prev_images);
        // }
        // else{
        //     $prev_images = null;
        // }   

        return view('designer.profile',['designer'=>$designer,'user'=>$user,'vote_exist'=>$vote_exist,'design_count'=>$current_designs,'featured_images'=>$fimage_array,'designs'=>$designs,'likes'=>$likes_count,'about'=>$about,'prev_works'=>$prev_works,'prev_count'=>$prev_work_count,'designs'=>$designs]);       
    }    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $designer = User::find($id);
        $designer->delete();
        return redirect()->route('home');
        // return redirect()->route('logout',$id);
    }
    
    public function savelikes(Request $request){
        $id = $request->get('id');
        $check =DesignerRate::where(['designer_id'=>$id,'liker_id'=> Auth::user()["id"]])->get();
            if($check->count() > 0 )
            {
                $designer = User::find($id);
                $likes  = $designer->likes - 1;
                $designer->likes = $likes ;
                $designer->save(); 

                DesignerRate::where(['designer_id'=>$id,'liker_id'=> Auth::user()["id"]])->delete();
                $exist =0;

            }
            else
            {
                $new_rate = new DesignerRate;
                $new_rate->designer_id = $id; 
                $new_rate->liker_id =Auth::user()["id"];
                $new_rate->save();
                $exist =1;
                $designer = User::find($id);
                $likes  = $designer->likes + 1;
                $designer->likes = $likes ;
                $designer->save();              
                
            }
        return response()->json(['success'=>'Got Simple Ajax Request.','likes'=>$likes,'input'=>$id,'exist'=>$exist]);
    }
    public function featuredesign(Request $request)
    {
        $specific_design = Design::find($request->get('id'));
        if ($specific_design->featured == 0)
        {
            $specific_design->featured = 1;
            $specific_design->save();
            $feature = 1;
        }
        $featured_image = DesignImage::where('design_id',$specific_design->id)->first();

        
        return response()->json(['success'=>'Got Simple Ajax Request','design_data'=>$specific_design,'design_image'=>$featured_image]);
        
    }
    public function deletefeaturedesign(Request $request)
    {
        $unfeatured_design = Design::find($request->get('id'));      
        $unfeatured_design->featured = 0;
        $unfeatured_design->save();
        $feature = 0;
        return response()->json(['success'=>'Got Simple Ajax Request','k'=>$unfeatured_design,'unfeatured_design'=>$unfeatured_design,'feature'=>$feature]);

    }
}
