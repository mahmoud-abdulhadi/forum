<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread ; 
use App\Channel ; 

use App\Filters\ThreadFilters ;

use Carbon\Carbon ; 

use App\Trending ; 
use Zttp\Zttp  ;


class ThreadsController extends Controller
{
    

	public function __construct(){


		$this->middleware('auth')->except(['index','show']);
	}

    /**
    * Method to fetch threads with filtering
    *
    */

    public function index(Channel $channel,ThreadFilters $filters ,Trending $trending){


       
        //apply all the filters applied in the request 

        $threads = $this->getThreads($channel,$filters);


        //good for testing and API 
        if(request()->wantsJson()){

            return $threads ; 
        }
    	  // 

    	return view('threads.index',[
            'threads' => $threads ,  

            'trends' => $trending->get()
        ]); 

    }



    public function show($channel,Thread $thread ,Trending $trending){

      

        //return $thread->load('replies') ; 
        //useful when you just concerned about count 
       // return Thread::withCount('replies')->find(51);

       // return $thread->replyCount ; 

        //finally added golabl scope

       // $replies = $thread->replies()->paginate(20);


        //Record that the user visited this page 
        //record it as timestamp 
        
        if(auth()->check()){

            auth()->user()->read($thread);
        }

        $trending->push($thread);

        $thread->increment('visits');

        /*$thread->visits()->record();*/

        /*
          */

        
        
    	return view('threads.show',compact(['thread']));
    }

    /** Show the form to create a thread */ 
    public function create(){



        return view('threads.create');
    }

    public function store(Request $request){

           
          $this->validate(request(),[

            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
            'channel_id' => 'required|exists:channels,id',
            'g-recaptcha-response' => 'recaptchavalid'
        ]);


      

    	//create a thread 

    	$thread = Thread::create([
    		'title' => $request->title,

    		'body' => $request->body,
            'channel_id' => $request->channel_id,
    		'user_id' => auth()->id(),


    	]);

        //Good for testing and APIs 
        if(request()->wantsJson()){

            return response($thread,201);
        }


    	//redirect to thread page 

    	return redirect($thread->path())
                ->with('flash','Your Thread has been published!');

    }

    public function destroy($channel,Thread $thread){
        //delete all the replies 

        $this->authorize('update',$thread);

        $thread->delete();

        if(request()->wantsJson()){
            return response([],204);

        }

        return redirect('/threads');
        

        
    }

    public function update($channel,Thread $thread){


        //authorization 

        $this->authorize('update',$thread);

        //validation 

         $this->validate(request(),[

            'title' => 'required|spamfree',
            'body' => 'required|spamfree',
        ]);

        //update 
        $thread->update(request(['title','body']));
    }


    public function getThreads($channel,ThreadFilters $filters){

       $threads =  Thread::latest()->filter($filters) ; 


        if($channel->exists){

            $threads->where('channel_id',$channel->id); 
        }

       // dd($threads->toSql());

        return $threads->paginate(25);

    }

}
