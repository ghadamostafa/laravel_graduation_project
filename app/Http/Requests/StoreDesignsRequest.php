<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class StoreDesignsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => $this->titleRules(),
            'price' => 'bail|required|numeric|between:0,9999.99',
            'description' => 'required|min:3|max:1000',
            'category' => 'required',
            'sourceFile'  => $this->sourceFileRules(),
            'images' =>  $this->imagesRules(),
            'images.*' => 'image|mimes:jpg,jpeg,png',
            'tag_id' => 'required',
            'Material' => 'required'
        ];
    }
    public function messages()
    {
        return [
            'tag_id.required' => 'The tag is required',
            'sourceFile.required' => 'The Source design pattron is required',
            'images.*.image'=> 'You have to specify an image',
            'images.*.mimes'=> 'The image must be in one of these formats jpg,jpeg,png',

        ];
    }
    public function titleRules(){
        // dd( $this->route()->getName());
        $rules=['bail','required','min:3','max:255'];
        $rules[] = $this->route()->getName() === 'designs.update'
        ?  'unique:designs,title,'.$this->route('design')->id
        : 'unique:designs';
        return $rules;
    }
    public function sourceFileRules()
    {
        $rules = $this->route()->getName() === 'designs.store'
        ? ['required']
        : ['sometimes'];
        array_push($rules,'mimes:pdf','max:10000');
        return $rules;
    }
    public function imagesRules()
    {

        $rules = $this->route()->getName() === 'designs.store'
        ? ['required']
        : ['sometimes'];
        return $rules;
    }
}
