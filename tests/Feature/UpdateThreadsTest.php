<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UpdateThreadsTest extends TestCase
{
    

    use DatabaseMigrations ; 

    public function setUp(){

    	parent::setUp();

    	$this->disableExceptionHandling();

    	$this->signIn();
    }


    /** @test*/ 
    function unauthorized_users_can_not_update_threads(){

    	

    	$thread = create('App\Thread', ['user_id' => create('App\User')->id]);

    	$this->patch($thread->path(),[])
    		->assertStatus(403); // forbidden 

    }


    /** @test */ 
    function a_thread_requires_a_title_and_body_to_be_updated(){
    	

    	$thread = create('App\Thread',['user_id' => auth()->id()]); 


    	$this->patch($thread->path(),[
    		'title' => 'Some Title'
    	])->assertSessionHasErrors('body');


    	$this->patch($thread->path(),[

    		'body' => 'Changed body'
    	])->assertSessionHasErrors('title');


    }

    /** @test */ 
    function a_thread_can_be_updated_by_its_creator()
    {


    

    	$thread = create('App\Thread',['user_id' => auth()->id()]);

    	//update
    	$this->patch($thread->path(),[
    		'title' => 'Changed Title', 
    		'body' => 'Changed Body'
    	]);


    	$this->assertEquals('Changed Title', $thread->fresh()->title);
    	$this->assertEquals('Changed Body',$thread->fresh()->body);


    }
}
