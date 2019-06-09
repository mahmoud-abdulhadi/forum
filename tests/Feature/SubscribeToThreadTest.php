<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SubscribeToThreadTest extends TestCase
{
    
    use DatabaseMigrations ; 

    /** @test */ 
    public function a_user_can_subscribe_to_threads()
    {


    	$this->signIn();

    	//We have a thread 
    	$thread = create('App\Thread');


    	//and the user subscribe to the thread .. 
    	$this->post($thread->path() . '/subscriptions');



        $this->assertCount(1,$thread->fresh()->subscriptions);

       

    }

    /** @test */ 
    public function a_user_can_unsubscribe_from_thread()
    {



        $thread = create('App\Thread') ; 

        $this->signIn();


        $thread->subscribe();


        $this->assertCount(1,$thread->subscriptions); 


        $this->delete($thread->path() . '/subscriptions');




        $this->assertCount(0,$thread->fresh()->subscriptions);



    }
}
