<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class FavoritesTest extends TestCase
{
   

   use DatabaseMigrations  ; 

   /** @test */ 

   public function a_guest_can_not_favorite_anything(){

   		$this->disableExceptionHandling()
   			 ->post('/replies/1/favorites')
   			->assertRedirect('/login');

   }


   /** @test */
  public function an_authenticated_user_can_favorite_any_reply(){

   		//If I post to a 'favorite' endpoint 

   		//plan the endpoint 
   		// /replies/id/favorites 
   		//as we need to fetch the reply 

   		//build the world 

  		$this->signIn();

   		$reply = create('App\Reply');

   		//we could use Route Model Binding 

   		$this->post('/replies/' . $reply->id.'/favorites') ; 


   		
   		//It should be recorded in the databse 

   		$this->assertCount(1,$reply->favorites);


   }

    /** @test */
  public function an_authenticated_user_can_unfavorite_a_reply(){

      //If I post to a 'favorite' endpoint 

      //plan the endpoint 
      // /replies/id/favorites 
      //as we need to fetch the reply 

      //build the world 

      $this->signIn();

      $reply = create('App\Reply');

      //we could use Route Model Binding 

      $reply->favorite();


      
      //It should be recorded in the databse 

      $this->assertCount(1,$reply->favorites);


      $this->delete('/replies/' . $reply->id . '/favorites') ; 

      $this->assertCount(0,$reply->fresh()->favorites);


   }


    /** @test */
  public function an_authenticated_user_may_only_favorite_a_reply_once(){


         //build the world 

      $this->signIn();

         $reply = create('App\Reply');

         //we could use Route Model Binding 

         $this->post('/replies/' . $reply->id.'/favorites') ; 


         $this->post('/replies/' . $reply->id.'/favorites') ;


         
         //It should be recorded in the databse 

         $this->assertCount(1,$reply->favorites);



   }

}
