<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Laravel\Scout\Searchable;

use App\User; 

use App\Channel ; 
use App\Activity ; 

use App\Filters\ThreadFilters ; 

use App\Notifications\ThreadWasUpdated ; 

use App\Events\ThreadHasNewReply ; 

use Illuminate\Support\Facades\Redis ; 



class Thread extends Model
{
    use RecordsActivity,Searchable; 

	protected $guarded = [] ; 

    protected $with = ['channel'];

    protected $appends = ['isSubscribedTo'] ; 

    protected $casts = [

        'locked' => 'boolean'
    ];


    protected static function boot(){

        parent::boot();

        
        static::addGlobalScope('creator',function($builder){


            $builder->with('creator');
        });

        static::deleting(function($thread){

            $thread->replies->each->delete();
          /*  $thread->replies->each(function($reply){

                $reply->delete();

            });*/

        });

        static::created(function($thread){


            $thread->update(['slug' => $thread->title]);

        });


        //instead you can boot using the model itself 
        /*
        static::created(function($thread){

            $thread->recordActivity('created') ; 

        });
        */

    }



   

    /**
    * Get A String path for the thread 
    *
    * @return string
    */
    public function path(){


    	return '/threads/' . $this->channel->slug . '/' .  $this->slug ; 
    }

    public function subscribe($userId = null)
    {

        $this->subscriptions()->create([

            'user_id' => $userId?:auth()->id()
        ]);

        return $this ; 
    }

    public function unsubscribe($userId = null)
    {

        $this->subscriptions()
            ->where('user_id',$userId?:auth()->id())
            ->delete();
    }

    public function replies(){


    	return $this->hasMany('App\Reply')
            ->latest();
    }

    public function subscriptions()
    {


        return $this->hasMany(ThreadSubscription::class);
    }

    public function creator(){


    	return $this->belongsTo(User::class,'user_id');
    }

    public function channel(){

        return $this->belongsTo(Channel::class); 
    }

    public function getReplyCountAttribute(){


        return $this->replies()->count();
    }

    //$reply here is an array of properties 
    
    public function addReply($reply){

        

        $reply =   $this->replies()->create($reply);

         //prepare notifications for all subscribers

        // more cheesy way to do it 

        // Event Based Approach

        event(new ThreadHasNewReply($this,$reply));

         return $reply ; 

      
        /*->each(function($subscription) use ($reply){

            $subscription->user->notify(new ThreadWasUpdated($this,$reply)) ; 

        });*/
    	   
       

       /* foreach($this->subscriptions as $subscription)
        {
            if($subscription->user_id != $reply->user_id){
                //Notify them wth new Event 
                $subscription->user->notify(new ThreadWasUpdated($this,$reply)); 
            }
        } */

      
    }
    /** Locking a thread */ 
    public function lock(){


        $this->update(['locked' => true]);
    }

    public function unlock(){


        $this->update(['locked' => false]);
    }


    public function notifySubscribers($reply)
    {


        $this->subscriptions
            ->where('user_id','!=',$reply->user_id)
            ->each
            ->notify($reply) ; 
    }

    public function scopeFilter($query,$filters){

        return $filters->apply($query);
    }


    public function getIsSubscribedToAttribute()
    {

        return $this->subscriptions()
                ->where('user_id',auth()->id())
                ->exists() ; 


    }

    public function getRouteKeyName(){



        return 'slug' ; 
    }

    public function setSlugAttribute($value){

        $slug = str_slug($value);


        if(static::whereSlug($slug)->exists()){

            $slug = "{$slug}-" . $this->id ;
        }

       

        $this->attributes['slug'] = $slug ;  

    }


    public function hasUpdatesFor($user)
    {


        //look in the cache for the proper key (carbon instace)

        //compare that carbon instance with the $thread->updated_at

        
        $key = $user->visitedThreadCacheKey($this) ; 

        return $this->updated_at > cache($key);
    }

    public function markBestReply(Reply $reply){


       $this->update(['best_reply_id' => $reply->id ]);

       

    }

    public function toSearchableArray(){
            return $this->toArray() + ['path' =>  $this->path()] ; 


    }


     /*public function visits(){


        return new Visits($this);
        

    }*/


   
}
