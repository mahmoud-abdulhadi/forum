<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class MentionUsersTest extends TestCase
{
   use DatabaseMigrations ; 


   /** @test */ 
   function mentioned_users_in_reply_are_notified()
   {

   		$john = create('App\User',['name' => 'johnDoe']); 

   		$this->signIn($john);

   		//create another user 
   		$mahmoud = create('App\User', ['name' => 'mahmoudhadi']);

   		//we have a thread 

   		$thread = create('App\Thread'); 

   		//John replies mentioning Mahmoud Hadi 
   		$reply = make('App\Reply',[

   			'body' => '@mahmoudhadi Look at this @FrankDoe'
   		]);

   		//Now The signedIn user will post a reply to the Thread 

   		$this->post($thread->path() . '/replies',$reply->toArray());


   		//Now , mahmoudhadi should be notified 

   		$this->assertCount(1,$mahmoud->notifications);

   }

   /** @test */ 
   function it_fetch_all_mentioned_users_starting_with_typed_characters()
   {

      $user1 = create('App\User' , ['name' => 'dondraper']);
      $user2 = create('App\User' , ['name' => 'donaldtrump']);

      $user3 = create('App\User' , ['name' => 'johndoe']);


      $results = $this->json('GET','/api/users',['name' => 'don']);

      $this->assertCount(2,$results->json());

   }
}
