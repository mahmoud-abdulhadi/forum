<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAvatarController extends Controller
{
    

    public function store(){

    	$this->validate(request(),[

    		'avatar' => ['required' , 'image']
    	]);

    	auth()->user()->update([
    		//hashed file name to prevent conflict 
    		'avatar_path' => request()->file('avatar')->store('avatars','public')

    	]);

        return response([],204);
    	//return back();
    }
}
