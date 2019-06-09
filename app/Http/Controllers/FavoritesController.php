<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Reply ; 

use App\Favorite ; 

class FavoritesController extends Controller
{
    //protect the functionality 
	public function __construct(){

		$this->middleware('auth');
	}

    public function store(Reply $reply){

    		 $reply->favorite();

    		 return redirect()->back();

    		

    }


     public function destroy(Reply $reply){

             $reply->unfavorite();

            

            

    }
}
