<?php 

namespace App\Filters ; 



use App\User ; 



class ThreadFilters  extends Filters {



	protected $filters = ['by','popular','unanswered'];
	

	

	//Query Builder Works through chaining 
	public function by($username){

		 $user = User::where('name',$username)->firstOrFail(); 

		  return $this->builder->where('user_id',$user->id) ; 


	}

	public function popular(){

		//remove existing order 

		$this->builder->getQuery()->orders = [];


		return $this->builder->orderBy('replies_count','desc');
	}

	public function unanswered()
	{


		return $this->builder->where('replies_count',0);

	}

}