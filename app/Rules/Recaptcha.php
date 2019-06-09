<?php 

namespace App\Rules ; 

use Zttp\Zttp ; 

class Recaptcha {



	public function passes($attribute,$value){

		  //Guzzle


        $response =  Zttp::asFormParams()->post('https://www.google.com/recaptcha/api/siteverify',[
                'secret' => config('services.recaptcha.secret'), 
                'response' => $value, 
                'remoteip' => request()->ip()
          ]);



		
        return $response->json()['success'] ; 

         

	}


}