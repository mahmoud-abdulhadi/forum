<?php

namespace App\Listeners;

use App\Events\ThreadHasNewReply;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User ; 

use App\Notifications\YouWereMentioned ; 

class NotifyMentionedUsers
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ThreadHasNewReply  $event
     * @return void
     */
    public function handle(ThreadHasNewReply $event)
    {
            

         User::whereIn('name',$event->reply->mentionedUsers())
                    ->get()
                    ->each(function($user) use ($event) {

                    $user->notify(new YouWereMentioned($event->reply)); 
                });


       
        /*$mentionedUsers = $event->reply->mentionedUsers() ; 

        foreach ($mentionedUsers as $name) {
            
                if($user = User::where('name',$name)->first()){

                    $user->notify(new YouWereMentioned($event->reply));
                }
            }*/


          
          
        }
    
}
