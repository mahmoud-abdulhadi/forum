<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Support\Facades\Redis ; 
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TrendingThreadsTest extends TestCase
{
   use DatabaseMigrations ; 

   public function setUp(){

   	parent::setUp();

      $this->trending = new \App\Trending(); 


   	$this->trending->reset();

   }


   /** @test */
   function it_increments_a_thread_score_each_time_it_is_read()
   {


   		$thread = create('App\Thread');

   		$this->assertEmpty($this->trending->get());

   		$this->call('GET',$thread->path());

   		$trends = $this->trending->get();

   		$this->assertCount(1,$trends);



   		$this->assertEquals($thread->title,$trends[0]->title);

         //after a test the threads is stil in Redis databas with key testing_tredning_threads
   }
}
