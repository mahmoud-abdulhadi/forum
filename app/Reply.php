<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use App\User ; 

use Carbon\Carbon ; 

class Reply extends Model
{
    
    use Favoritable , RecordsActivity ; 


    protected static function boot()
    {


        parent::boot();

        static::created(function($reply){

            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply){


            //best Reply Bug Solution on Server 
           if($reply->isBest){

                $reply->thread->update(['best_reply_id' => null]); 

            }

            $reply->thread->decrement('replies_count');
        });
    }
    
	protected $guarded = [] ; 

    protected $with=['owner','favorites'];


    //added when the model is casted into json object 
    protected $appends = ['favoritesCount','isFavorited','isBest'];


    public function thread(){

    	return $this->belongsTo('App\Thread'); 
    }

    public function owner(){

    	return $this->belongsTo(User::class,'user_id');
    }

    public function path(){


    	return $this->thread->path() . "#reply-{$this->id}" ; 
    }

    public function wasJustPublished(){


        return $this->created_at->gt(Carbon::now()->subMinute()); 
    }

    public function mentionedUsers()
    {


        preg_match_all('/@([\w\-]+)/',$this->body, $matches);


        return $matches[1];
    }

    //wrap mentioned users in the body with anchor tags 

    public function setBodyAttribute($body){

        $this->attributes['body'] = preg_replace('/@([\w\-]+)/', '<a href="/profiles/$1">$0</a>',$body);

    }

    

    public function getIsBestAttribute(){

        return $this->thread->best_reply_id == $this->id; 
      

    }

  
}
