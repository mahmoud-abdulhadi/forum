<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ParticipateInForumTest extends TestCase
{
    use DatabaseMigrations ; 




    /** @test */ 
    public function unauthenticated_user_may_not_add_replies(){

    	$this->disableExceptionHandling()
    	 ->post('/threads/new-channel/1/replies',[])
    	 ->assertRedirect('/login');

    }

	/** @test */

	function an_authenticated_user_may_participate_in_forum_threads(){


		//Given we have an authenticated user 
		/*$user = factory('App\User')->create(); 

		$this->be($user);*/

		//or the professional way 
		$this->signIn();

		

		//and there is an exsiting thread 


		//$thread = factory('App\Thread')->create();

		//using helpers 

		$thread = create('App\Thread');
		//when the user adds a reply 
		//$reply = factory('App\Reply')->make();

		//using helpers 
		$reply = make('App\Reply');

		$this->post($thread->path().'/replies',$reply->toArray());

		//it should be visible on the thread page 
		//The Reply now is loaded  with Javascript 

		//better to test it with database 
		$this->assertDatabaseHas('replies',['body' => $reply->body]);

		$this->assertEquals(1,$thread->fresh()->replies_count);

	}

	/** @test */ 
	function a_reply_requires_a_body(){


		$this->disableExceptionHandling();

		$this->signIn();


		$thread = create('App\Thread'); 


		$reply = make('App\Reply',['body' => null]);

		$this->post($thread->path() .'/replies',$reply->toArray())
			->assertSessionHasErrors('body'); 


	}

	/** @test */ 
	public function unauthorized_users_cannot_delete_replies()
	{

			$this->disableExceptionHandling();


			$reply = create('App\Reply');

			$this->delete("/replies/{$reply->id}")
				->assertRedirect('login');

			$this->signIn()
				->delete("/replies/{$reply->id}")
				->assertStatus(403); //forbidden status 

	}


	/** @test */ 
	public function authorized_users_can_delete_replies()
	{


		$this->signIn();

		$reply = create('App\Reply',['user_id' => auth()->id()]);

		$this->delete("/replies/{$reply->id}")
			->assertStatus(302);


		$this->assertDatabaseMissing('replies',['id' => $reply->id]);

		$this->assertEquals(0,$reply->thread->fresh()->replies_count);


	}


	/** @test */ 
	public function unauthorized_users_cannot_update_replies()
	{

			$this->disableExceptionHandling();


			$reply = create('App\Reply');

			$this->patch("/replies/{$reply->id}")
				->assertRedirect('login');

			$this->signIn()
				->patch("/replies/{$reply->id}")
				->assertStatus(403); //forbidden status */

	}


	/** @test */ 
	public function authorized_users_can_update_replies()
	{

		$this->signIn();

		$reply = create('App\Reply',['user_id' => auth()->id()]);

		$updatedReply = 'Something have been changed!!' ; 

		$this->patch("/replies/{$reply->id}",['body' => $updatedReply]);

		$this->assertDatabaseHas('replies',['id' => $reply->id,'body' => $updatedReply ]);

	}


	/** @test */
	function replies_that_contain_spam_may_not_be_created(){

		$this->disableExceptionHandling();

		$this->signIn();


		$thread = create('App\Thread');


		$reply = make('App\Reply',[


			'body' => 'WANT TO LOSE WEIGHT?'

		]);


		
		
		$this->json('post',$thread->path() . '/replies' , $reply->toArray())
			->assertStatus(422);


	}


	/** @test */ 
	public function users_may_only_reply_a_maximum_of_once_per_minute()
	{

		$this->disableExceptionHandling();


		$this->signIn();


		$thread = create('App\Thread');


		$reply = make('App\Reply',[


			'body' => 'Simple Reply'

		]);


		$this->post($thread->path() . '/replies' , $reply->toArray())
			->assertStatus(200);


		$this->post($thread->path() . '/replies' , $reply->toArray())
			->assertStatus(429);

	}

}
