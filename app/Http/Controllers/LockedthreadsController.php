<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Thread;

class LockedthreadsController extends Controller
{
    

    public function store(Thread $thread){


            $thread->lock();

  
    }

    public function destroy(Thread $thread){

    	$thread->unlock();
    }
}
