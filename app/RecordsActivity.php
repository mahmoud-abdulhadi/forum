<?php 

namespace App ; 




trait RecordsActivity { 


	//convention 
	//boot + name of the trait 

	 protected static function bootRecordsActivity()
	 {

	 	if(auth()->guest()) return ; 

	 	foreach(static::getEventsToRecord() as $event){

	 		static::$event(function($model) use ($event){

            $model->recordActivity($event) ; 

        });

	 	}

	 	static::deleting(function($model){

	 		$model->activity()->delete();
	 	});

	 }


	 protected function recordActivity($event)
	    {

	    	$this->activity()->create([
	    		'user_id' => auth()->id(),
	    		'type' =>   $this->getActivityType($event)


	    	]);


	    	/*
	        Activity::create([
	                'user_id' => auth()->id(),
	                'type' =>   $this->getActivityType($event), 
	                'subject_id' => $this->id, 
	                'subject_type' => get_class($this) 
	            ]);
	        */
	    }

	    
	    // The model that I am listening for 
	    //what events should I Record activity 
	    protected static function  getEventsToRecord(){

	    	return ['created']; 


	    }


	    public function activity(){

	    	return $this->morphMany('App\Activity','subject');	

	    }

	    protected function getActivityType($event){

	    	$type = strtolower((new \ReflectionClass($this))->getShortName()) ;


	    	return "{$type}_{$event}" ;
	      
	    }



}