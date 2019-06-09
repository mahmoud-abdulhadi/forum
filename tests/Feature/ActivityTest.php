<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Activity ; 
use Carbon\Carbon  ;

class ActivityTest extends TestCase
{
    

    use DatabaseMigrations ; 


    /** @test */ 
    public function it_recoreds_activity_when_a_thread_is_created(){

    	$this->signIn();

    	$thread = create('App\Thread');

    	$this->assertDatabaseHas('activities',[
    		'type' => 'thread_created',
    		'user_id' => auth()->id(),
    		'subject_id' => $thread->id,
    		'subject_type' => 'App\Thread'

    	]);

    	$activity = Activity::first();

    	$this->assertEquals($activity->subject->id , $thread->id);
    }


    /** @test */ 
    public function it_records_activity_when_a_reply_is_created(){

    	$this->signIn() ; 


        //it creates a thread in the process
    	$reply = create('App\Reply'); 

    	$this->assertEquals(2,Activity::count()) ; 

    }

    /** @test */ 
    public function it_fetches_a_feed_for_any_user()
    {
         $this->signIn(); 

         $thread = create('App\Thread',['user_id' => auth()->id()],2);


         
         //manually make one activity one week before
         auth()->user()->activities()->first()->update(['created_at' => Carbon::now()->subWeek()]);

         //when we fetch their feed 
         $feed = Activity::feed(auth()->user());


         $this->assertTrue($feed->keys()->contains(
            Carbon::now()->format('Y-m-d')
         ));

         $this->assertTrue($feed->keys()->contains(
            Carbon::now()->subWeek()->format('Y-m-d')
         ));
    }
}
