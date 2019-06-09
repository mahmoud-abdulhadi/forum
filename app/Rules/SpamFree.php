<?php 


namespace App\Rules ; 

use App\Inspections\Spam ; 

class SpamFree {



	/** 
	* $attribute the name of the input from the form 
	*/


	public function passes($attribute,$value)
	{

		try{

			return ! resolve(Spam::class)->detect($value);

		}catch(\Exception $e){


			return false ; 
		}

		

	}

}