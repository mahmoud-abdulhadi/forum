<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class LockThreadTest extends TestCase
{
    
	use DatabaseMigrations ; 

	/** @test */ 
	function non_administrators_may_not_lock_threads(){

		$this->disableExceptionHandling();

		$this->signIn();

		$thread = create('App\Thread',['user_id' => auth()->id()]);

		$this->post(route('locked-threads.store',$thread))->assertStatus(403);


		$this->assertFalse( $thread->fresh()->locked);


	}

	/** @test */ 
	function administrators_can_lock_any_thread(){

		$this->signIn(factory('App\User')->states('administrator')->create());

		$thread = create('App\Thread');

		$this->post(route('locked-threads.store',$thread));


		$this->assertTrue($thread->fresh()->locked,'Falied asserting that the thread was locked');


	}

	/** @test */ 
	function administrators_can_unlock_any_threads(){

		$this->signIn(factory('App\User')->states('administrator')->create());


		$thread = create('App\Thread', ['locked' => true]);

		$this->deletejson(route('locked-threads.destroy',$thread));

		$this->assertFalse($thread->fresh()->locked,'Failed asserting that the thread was unlocked');

	}


	/** @test */ 
	function once_locked_a_thread_may_not_receive_new_replies(){

		$this->signIn();


		$thread = create('App\Thread');

		$thread->lock(); 


		//After locking the thread should don't receive any reply 
		$this->post($thread->path().'/replies',[
			'body' => 	'Some Nice Comment!!',
			'user_id' => auth()->id()

		])->assertStatus(422);




	}

  }
