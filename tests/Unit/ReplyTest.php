<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


use Carbon\Carbon ; 

class ReplyTest extends TestCase
{
	use DatabaseMigrations ; 
   

    /**
	*
	* @return void
	* @test
    */	
    public function a_reply_has_an_owner(){	

    		//given we created a reply 

    		//$reply = factory('App\Reply')->create();

        //using helpers 

        $reply = create('App\Reply');
    		//it has an owner instance of App\User 

    		$this->assertInstanceOf('App\User',$reply->owner);

    }

    /** @test */ 
    public function it_know_if_it_was_just_published()
    {


        $reply = create('App\Reply');

        $this->assertTrue($reply->wasJustPublished());


        $reply->created_at = Carbon::now()->subHour();

        $this->assertFalse($reply->wasJustPublished());


    }

    /** @test */ 
    function it_can_detect_mentioned_users()
    {

        $reply = new \App\Reply([

            'body' => '@JaneDoe Wants to talk to @JohnDoe'
        ]);

        $this->assertEquals(['JaneDoe','JohnDoe'],$reply->mentionedUsers());
    }

    /** @test */
    function it_wraps_mentioned_users_in_body_with_anchor_tags(){

        $reply = new \App\Reply([

            'body' => 'Hello,@Jane-Doe!!.'
        ]);

        $this->assertEquals('Hello,<a href="/profiles/Jane-Doe">@Jane-Doe</a>!!.',$reply->body);
    }

    /** @test */ 
    function it_knows_if_it_is_the_best_reply(){

        $reply = create('App\Reply');

        $this->assertFalse($reply->isBest);

        $reply->thread->update(['best_reply_id' => $reply->id]);


        $this->assertTrue($reply->fresh()->isBest);



    }
}
