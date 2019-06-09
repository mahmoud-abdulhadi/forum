<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use App\Inspections\Spam ; 

class SpamTest extends TestCase
{
   use DatabaseMigrations ; 


   /** @test */
   function it_checks_for_invalid_keywords(){

   		$spam = new Spam();
		$this->assertFalse($spam->detect('Innocent Reply Here!'));

		$this->expectException('Exception');


		$spam->detect('want to lose weight?');
   }

   /** @test */ 
   function it_checks_for_any_key_being_held_down(){

   		$spam = new Spam(); 


   		$this->expectException('Exception');

   		$spam->detect('Hello World aaaaaaa');
   }
}
