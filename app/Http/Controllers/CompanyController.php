<?php

namespace App\Http\Controllers;

use App\CompanyDesign;
use App\Design;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Response;


class CompanyController extends Controller
{
    public function __construct() {
        $this->middleware(['auth','check-role:company'], ['only' => ['show_create_design_form']]);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies=User::whereHas('profile', function($query) {
            $query->where('is_verified','=','accepted');})->where('role','=','company')->get();
        return view('companies.index',compact('companies'));
    }

    /**
     * Show the form for creating a new desing.
     *
     * @return \Illuminate\Http\Response
     */
    public function show_create_design_form()
    {
        //
        $company = Auth::user();
        return view('companies.add_design',compact(['company']));
    }

    /**
     * Store a newly created design in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create_design(Request $request)
    {
        //
        $user=Auth::user();
        $this->design_validator($request->all())->validate();
        event(new Registered($design = $this->create_new_design($request->all())));
        return $request->wantsJson()
                    ? new Response('', 201)
                    : redirect()->route('company.shop',$user);
    }

    protected function design_validator(array $data)
    {
        return Validator::make($data, [
            'design' => ['required'],
            'link' => ['required'],
            'image'=>['required','image'],
            'title'=>['required','string','min:5'],
            'price'=>['required','numeric']
        ]);
    }

    protected function create_new_design($data)
    {
        $design=Design::find($data['design']);
        $user=Auth::user();
        if($user->can('create_company_design',$design)){
            $image_path = $data['image']->store('uploads', 'public');
    
            return CompanyDesign::updateOrCreate(['design_id' => $data['design']],[
                'company_id'=>Auth::user()->id,
                'link' => $data['link'],
                'title' => $data['title'],
                'price' => $data['price'],
                'image' => $image_path,
            ]);
        }else{
            return abort(403,'u dont own this design');
        }
        
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $company)
    {
        $company=User::find($company->id);
        return view('companies.show',compact('company'));
    }

    public function shop(User $user)
    {
        $company=$user;
        return view('companies.shop',compact('company'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $company)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
