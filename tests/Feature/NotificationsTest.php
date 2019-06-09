<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Notifications\DatabaseNotification ; 

class NotificationsTest extends TestCase
{
    use DatabaseMigrations ; 

    public function setUp(){


        parent::setUp();


        $this->signIn();
    }

    /** @test */ 
    public function a_notification_should_be_prepared_when_subscribed_thread_recieves_a_reply_that_is_not_by_the_current_user(){

    		


    		$thread = create('App\Thread')->subscribe();

    		$this->assertCount(0,auth()->user()->notifications);

    	//then each time a new reply is left by te current use 

    	$thread->addReply([
    		'user_id' => auth()->id(),
    		'body' => 'Some New Reply Is Added'
    	]);

    	//A notification should not be prepared for the user 

        $this->assertCount(0,auth()->user()->fresh()->notifications);

    	$thread->addReply([
    		'user_id' => create('App\User')->id, 
    		'body' => 'Positive Reply'

    	]);



    	$this->assertCount(1,auth()->user()->fresh()->notifications);
   

    }

    /** @test */
    public function a_user_can_fetch_their_unread_notifications()
    {
    	


        create(DatabaseNotification::class);

    	


    	$this->assertCount(1,
            $this->getJson("/profiles/" . auth()->user()->name ."/notifications")->json()
            );



    }


    /** @test */ 
    public function a_user_can_mark_a_notification_as_read(){

    		
            create(DatabaseNotification::class);

    		//$thread = create('App\Thread')->subscribe();


    	/*	$thread->addReply([
    		'user_id' => create('App\User')->id, 
    		'body' => 'Positive Reply'

    		]);*/


            tap(auth()->user(),function($user){

                $this->assertCount(1,$user->unreadNotifications);


                $this->delete("/profiles/" . $user->name."/notifications/".$user->unreadNotifications->first()->id);

                $this->assertCount(0,$user->fresh()->unreadNotifications);



            });


    }

}
