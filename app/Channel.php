<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    



    public function getRoutekeyName(){
    	return 'slug'; 
    	
    }

    
    public function threads(){

    	return $this->hasMany('App\Thread'); 
    }
}
