<?php

	namespace Tests\Feature;
	
	use Tests\TestCase;
	use Illuminate\Foundation\Testing\WithoutMiddleware;
	use Illuminate\Foundation\Testing\DatabaseMigrations;
	use Illuminate\Foundation\Testing\DatabaseTransactions;
	
	class ChannelTest extends TestCase
	{
	    use DatabaseMigrations ; 
	
	
	    /** @test */ 
	    public function a_channel_consists_of_threads(){

	    	//Create a channel 
	    	$channel = create('App\Channel');


	    	//create a threads that nelongs to that channel 
	    	$thread = create('App\Thread',['channel_id' => $channel->id]);

	    	//when we fetch  the channel threads 
	    	//it contains that thread

	    	$this->assertTrue($channel->threads->contains($thread)); 


	    }
	
	}
