<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread ; 
use App\Channel ; 

use App\Reply ; 

use App\Notifications\YouWereMentioned;

use Gate ; 

use App\User ; 

use App\Http\Requests\CreatePostRequest ; 



class RepliesController extends Controller
{
    public function __construct(){


    	$this->middleware('auth' ,[ 'except' => 'index']);
    }


    public function index($channelId,Thread $thread)
    {
    
       return $thread->replies()->paginate(20);

    }

    public function store($channel,Thread $thread,CreatePostRequest $form){



      //Inspect the body of the reply for username mentions 
      //and then for each mentioned user, notify them.
      // There is firing of event in the addReply of Thread 
       
      //validation In the Request $form 

            if($thread->locked){

                 return response('You do not hoave persmission This Thread is locked',422);
            }
         return   $thread->addReply([
                'body' => request('body'),
                'user_id' => auth()->id()
                ])->load('owner');


        


    }


    public function update(Reply $reply)
    {

        $this->authorize('update',$reply);



        $this->validate(request(),[
                'body' => 'required|spamfree'

            ]);


        $reply->update(request(['body'])) ; 


      
    }

    public function destroy(Reply $reply)
    {
        //protect the deletion of a Reply
        
        $this->authorize('update',$reply);

        $reply->delete();


        if(request()->expectsJson()){

            return response(['status' => 'Reply Deleted']); 
        }

        return back();

    }

  
}
