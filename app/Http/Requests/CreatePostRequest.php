<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Exceptions\ThrottleException ; 

use Gate ; 

class CreatePostRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Gate::allows('create' , new \App\Reply); 
    }


    protected function failedAuthorization()
    {


        throw new ThrottleException(
            'You are replying too frequestnly.Please Take A Break!!'
        ); 

    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'body' => 'required|spamfree'
        ];
    }

}