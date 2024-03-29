<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Http\UploadedFile ; 

use Storage ; 

class AddAvatarTest extends TestCase
{
    use DatabaseMigrations ; 


    /** @test */
    function only_members_can_add_avatars()
    {

    	$this->disableExceptionHandling();


    	$this->json('POST','/api/users/1/avatar')
    		->assertStatus(401);
    }

    /** @test */
    function a_valid_avatar_must_be_provided()
    {

    	$this->disableExceptionHandling()->signIn();


    	$this->json('POST','/api/users/' . auth()->id() .  '/avatar',[

    			'avatar' => 'not-an-image'

    		])->assertStatus(422);
   


    }


    /** @test */ 
    function a_user_may_add_an_avatar_to_their_profile()
    {


    	$this->signIn();

    	Storage::fake('public');

    	$this->json('POST','/api/users/' . auth()->id() .'/avatar',[
    		'avatar' => $file = UploadedFile::fake()->image('avatar.jpg')
    	]);


    	$this->assertEquals('/storage/avatars/'.$file->hashName(),auth()->user()->avatar_path);


    	Storage::disk('public')->assertExists('avatars/' .$file->hashName());
    }

}
