<?php 


namespace App\Inspections ; 




class InvalidKeywords { 


	protected $invalidKeywords = [

			'want to lose weight?'

		];



	public function detect($body){


		

		foreach($this->invalidKeywords as $keyword){

			if(stripos($body,$keyword) !== false){

				throw new \Exception('Your Reply contains spam.');
			}
		}
	}
}

