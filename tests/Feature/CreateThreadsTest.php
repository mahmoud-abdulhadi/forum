<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Rules\Recaptcha ; 

use App\Activity ; 

use App\Thread ; 

class CreateThreadsTest extends TestCase
{
    use DatabaseMigrations ; 

    public function setUp(){

        parent::setUp(); 
        app()->singleton(Recaptcha::class,function(){

           return  \Mockery::mock(Recaptcha::class,function($m){


                $m->shouldReceive('passes')->andReturn(true);

            }); 

        });


    }

    /** @test */
    function a_guest_may_not_create_threads(){


    	$this->disableExceptionHandling();

        $this->get('/threads/create')
            ->assertRedirect('/login'); 
/*
        $this->post('/threads')
            ->assertRedirect('/login');
*/
}

   

	/** @test */ 
     function an_authenticated_user_can_create_new_threads(){


    	//Given we have an authenticated user 

    	//$this->actingAs(create('App\User'));

        //using helpers 

      //  $this->signIn();

    	//when we hit the endpoint to create a new thread

    	//$thread = make('App\Thread'); 

    	

    	//$response = $this->post('/threads',$thread->toArray()+['g-recaptcha-response' => 'token']);

       $response =  $this->publishThread(['title' => 'Some Title' , 'body' =>'Some Body']);
        //dd($response->headers->get('Location'));

    	//Then,when we visit the thread page
    	//we should see the new thread

       

    	$this->get($response->headers->get('Location'))
    		->assertSee('Some Title')
    			->assertSee('Some Body'); 	

    		
    }

    /** @test */ 
    function a_thread_requires_a_title(){


        $this->publishThread(['title' => null])
            ->assertSessionHasErrors('title');

       

    }
    /** @test */ 
    function a_thread_requires_a_unique_slug()
    {


        $this->signIn();


       

        $thread =  create('App\Thread',['title' => 'Foo Title']);

        $this->assertEquals('foo-title',$thread->fresh()->slug);

       $thread = $this->postJson(route('threads'),$thread->toArray())->json();




       $this->assertEquals("foo-title-{$thread['id']}",$thread['slug']);

        

    }

    /** @test */
    function a_thread_with_a_title_that_ends_numeric_should_generate_proper_slug(){


        $this->signIn();


        $thread = create('App\Thread',['title' => 'Some Title 24']); 

        $thread = $this->postJson(route('threads'),$thread->toArray())->json();


        $this->assertEquals("some-title-24-{$thread['id']}",$thread['slug']);
    }

    /** @test */

    function a_thread_requires_a_body(){



        $this->publishThread(['body' => null])
            ->assertSessionHasErrors('body');

    }


    /** @test */ 
    function a_thread_requires_channel_id(){

        factory('App\Channel',2)->create();

        $this->publishThread(['channel_id' => null])
            ->assertSessionHasErrors('channel_id');


        $this->publishThread(['channel_id' => 9999])
                ->assertSessionHasErrors('channel_id'); 
    }

    /** @test */ 
   /* function a_thread_requires_recaptcha_verification(){


        unset(app()[Recaptcha::class]);

        $this->publishThread(['g-recaptcha-response' => 'test'])
            ->assertSessionhasErrors('g-recaptcha-response');
           


    }*/

    function publishThread($overrides = []){

       $this->disableExceptionHandling();
        $this->signIn();

        $thread = make('App\Thread',$overrides);

       // dd($thread);

        return $this->post('/threads',$thread->toArray());
        

    }

    /** @test */ 
    function unauthorized_users_may_not_delete_threads(){

        $this->disableExceptionHandling();

        $thread = create('App\Thread');

         $this->delete($thread->path())
            ->assertRedirect('/login');

        $this->signIn();

        $this->delete($thread->path())
            ->assertStatus(403);

}

    

    /** @test */ 
    function authorized_user_can_delete_threads(){

        $this->signIn();

        $thread = create('App\Thread',['user_id' => auth()->id()]);

        $reply = create('App\Reply',['thread_id' => $thread->id]);


        $response = $this->json('DELETE',$thread->path());

        $response->assertStatus(204);

        $this->assertDatabaseMissing('threads',['id'=>$thread->id]);

        $this->assertDatabaseMissing('replies',['id' => $reply->id]);

        /*$this->assertDatabaseMissing('activities',[
            'subject_id' => $thread->id,
            'subject_type' => get_class($thread)
        ]);

         $this->assertDatabaseMissing('activities',[
            'subject_id' => $reply->id,
            'subject_type' => get_class($reply)
        ]);*/

        $this->assertEquals(0,Activity::count());

    }
}
