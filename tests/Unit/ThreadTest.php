<?php

namespace Tests\Unit;

use Tests\TestCase;

use Illuminate\Support\Facades\Notification ; 
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Redis;

use App\Notifications\ThreadWasUpdated ; 

class ThreadTest extends TestCase
{
    
		use DatabaseMigrations ; 

		protected $thread ; 


		public function setUp(){

			parent::setUp();


			//Create A thread to used by methods 
			//$this->thread = factory('App\Thread')->create(); 

			//using helpers 

			$this->thread = create('App\Thread');
		}

		/** @test */ 
		function a_thread_has_a_path(){


			$thread = create('App\Thread'); 


			$this->assertEquals('/threads/' . $thread->channel->slug.'/' . $thread->slug,$thread->path() ); 
			
		}

		/** @test */
		public function a_thread_has_replies(){


			//Create a thread 

			


			//check the replies of the thread
			$this->assertInstanceOf('Illuminate\Database\Eloquent\Collection',$this->thread->replies);
		}

		/** @test **/ 
		function a_thread_has_creator(){

			

			//when we fetch the creator of the thread is instance of User Class 
			$this->assertInstanceOf('App\User',$this->thread->creator);


		}

		/** @test */ 
		function a_thread_can_add_Reply(){
			$this->thread->addReply([

				'body' => 'Great Work!',
				'user_id' => 1

			]);


			$this->assertCount(1,$this->thread->replies);

		}

		/** @test */ 
		function a_thread_has_a_channel(){


		

			$this->assertInstanceOf('App\Channel',$this->thread->channel);
		}

		/** @test */
		function a_thread_can_be_subscribed_to()
		{

			
			$thread = create('App\Thread'); 			

			//when the user subscribes to the thread 
			$thread->subscribe($userId = 1);



			$this->assertEquals(1,$thread->subscriptions()->where('user_id',1)->count());

		}


		/** @test */
		public function a_thread_can_be_unsubscribed_from()
		{

			$thread = create('App\Thread'); 


			$thread->subscribe($userId = 1);


			$thread->unsubscribe(1);


			$this->assertCount(0,$thread->subscriptions);

		}

		/** @test */ 
		public function it_knows_if_authenticated_user_is_subscribed_to_it(){



			$thread = create('App\Thread'); 

			$this->signIn();

			$this->assertFalse($thread->isSubscribedTo);

			$thread->subscribe();

			$this->assertTrue($thread->isSubscribedTo);
		}

		/** @test */
		function a_thread_notifies_all_registered_subscribers_when_a_reply_is_added()
		{

			Notification::fake();

			$this->signIn()
				->thread->subscribe()
				->addReply([

				'body' => 'Great Work!',
				'user_id' => 999

			]);


			Notification::assertSentTo(auth()->user(),ThreadWasUpdated::class);


		}

		/** @test */ 
		function a_thread_can_check_if_the_authenticated_user_has_read_all_replies()
		{



			$this->signIn();

			$thread = create('App\Thread');

			tap(auth()->user() , function($user) use ($thread){

				$this->assertTrue($thread->hasUpdatesFor($user));


				//simulate that the user has read the thread 
			


       		 	$user->read($thread);

       		 	$this->assertFalse($thread->hasUpdatesFor($user));

			});
		
       		 
		}


		/** @test */ 
		function a_thread_may_be_locked(){

			$this->assertFalse($this->thread->locked);

			$this->thread->lock();


			$this->assertTrue($this->thread->locked);


		}

	/*
		function a_thread_records_each_visit(){

			$thread = make('App\Thread',['id' => 1]);

			//clear the Redis Cache 

			$thread->visits()->reset(); //create New Visit

			//$thread->resetvisits();
			
			$this->assertSame(0,$thread->visits()->count());

			$thread->visits()->record(); 

			$this->assertEquals(1,$thread->visits()->count());


			$thread->recordsVisit();

			$this->assertEquals(2,$thread->visits());


		}*/

		
	

}
