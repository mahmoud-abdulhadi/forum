<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ReadThreadsTest extends TestCase
{
    use DatabaseMigrations ; 



    public function setUp(){


        parent::setUp(); 


        //$this->thread = factory('App\Thread')->create(); 


        //using helpers 
        $this->thread = create('App\Thread');
    }
   
    /**
     *@test
     */
    public function a_user_can_view_all_threads(){

        
        //We visit the index route 

        //we assume we get status ok 

         //we see the thread title 
         $this->get('/threads')
                ->assertStatus(200)
                 ->assertSee($this->thread->title);

    }

    /**
    *@test
    */
    public function a_user_can_read_single_thread(){

     

        //we visit the route to it 
        //we assert we see the thread title
            $this->get($this->thread->path())
                    ->assertSee($this->thread->title); 

    }

    

    /** @test */
    function a_user_can_filter_threads_according_to_a_channel(){

        //We Create a channel 


        $channel = create('App\Channel');


        //create a thread belongs to that channel 


        $threadInChannel = create('App\Thread',['channel_id' => $channel->id]); 


        //create a thread that doesn't belong to that channel 

        $threadNotInChannel = create('App\Thread'); 


        //when we visit the channel 

        $this->get('/threads/' . $channel->slug)

        //we see the thread in the channel 
            ->assertSee($threadInChannel->body)


        //but don't see the thread that doesn't belong to the channel 
            ->assertDontSee($threadNotInChannel->body);
    }

    /** @test */ 
    function a_user_can_filter_threads_by_any_username(){

        //Create A User 
        $this->signIn(create('App\User',['name' => 'JohnDoe'])); 


        //Create a thread by that user 
        $threadByJohn = create('App\Thread',['user_id' => auth()->id()]);


        //Create a thread not by that user 

        $threadNotByJohn = create('App\Thread');


        //when we visit filter threads we see threads by that user 


        $this->get('threads?by=JohnDoe')
            ->assertSee($threadByJohn->title)
                ->assertDontSee($threadNotByJohn->title); 

    }

    /** @test */
    function a_user_can_filter_threads_by_popularity(){

        //Given we have three threads 

        //with 2 replies, 3 replies , and 0 replies

        //set up the world 

        $threadWithTwoReplies = create('App\Thread'); 

        create('App\Reply',['thread_id' => $threadWithTwoReplies->id ],2);

        $threadWithThreeReplies = create('App\Thread'); 

        create('App\Reply',['thread_id' => $threadWithThreeReplies->id ],3);

        $threadWithZeroReplies = $this->thread ; 



        //when I Filter all threads by popularity 

        $response = $this->getJson('threads?popular=1')->json();

        

        //then they should be returned from most replies to least 

        //pluck the replies_count column 

        

        $this->assertEquals([3,2,0],array_column($response['data'],'replies_count'));


    }

    /** @test */ 
    public function a_user_can_filter_threads_by_those_that_are_not_answered()
    {



        $thread = create('App\Thread');

        $reply = create('App\Reply',['thread_id' => $thread->id]);

        $response = $this->getJson('threads?unanswered=1')->json();


        $this->assertCount(1,$response['data']);

    }

    /** @test */
    public function a_user_can_request_all_replies_for_a_given_thread()
    {

        $thread = create('App\Thread'); 

        $reply = create('App\Reply',['thread_id' => $thread->id],21);

        $response = $this->getJson($thread->path() . '/replies')->json(); 

        $this->assertCount(20,$response['data']);

        

       $this->assertEquals(21,$response['total']);

    }

    /** @test */ 

    function it_adds_new_visit_each_time_a_thread_is_read(){

        $thread = create('App\Thread');


        $this->assertSame(0,$thread->visits);


        $this->call('GET',$thread->path());

        $this->assertEquals(1,$thread->fresh()->visits);


    }




}
