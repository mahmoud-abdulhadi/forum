<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','avatar_path'
    ];

    public function getRoutekeyName(){


        return 'name';
    }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    //remember to add the email 
    protected $hidden = [
        'password', 'remember_token'
    ];

    public function threads(){


        return $this->hasMany('App\Thread')->latest();
    }


    /*
    *A User has many activities 
    */
    public function activities(){

        return $this->hasMany('App\Activity');
    }

    public function visitedThreadCacheKey($thread){


       return sprintf("users.%s.visits.%s",$this->id,$thread->id);
    }

    public function read($thread){

        cache()->forever(
            $this->visitedThreadCacheKey($thread) , 
                \Carbon\Carbon::now()
                );
    }

    public function getAvatarPathAttribute($avatar){

        if($avatar){
           
            return '/storage/' . $avatar ; 
        }
        
        return asset('imgs/avatars/default.png');
        
        
    }

    public function lastReply()
    {

        return $this->hasOne(Reply::class)->latest();
    }

    public function isAdmin(){


        return in_array($this->name,[

            'MahmoudHadi',
            'JohnDoe'
        ]);

    }

   
}
