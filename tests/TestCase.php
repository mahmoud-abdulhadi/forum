<?php

namespace Tests;


use Illuminate\Contracts\Debug\ExceptionHandler ; 
use App\Exceptions\Handler ; 
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;


abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(){

    	parent::setUp();

        \DB::statement('PRAGMA foreign_kyes=on;');

    	$this->enableExceptionHandling();
    }


    protected function signIn($user = null){


    	$user = $user ? : create('App\User'); 

    	$this->actingAs($user); 

    	return $this; 
    }

    protected function disableExceptionHandling(){


    	$this->app->instance(ExceptionHandler::class,$this->oldExceptionHandler);

    	return $this ; 

    }


    protected function enableExceptionHandling(){

    	$this->oldExceptionHandler = $this->app->make(ExceptionHandler::class);

    	$this->app->instance(ExceptionHandler::class, new class extends handler{

    		public function __construct() {}
    		public function report(\Exception $ex){}

    		public function render($request , \Exception $ex) {

    			throw $ex;
    		}

    	});
    }


    


}
