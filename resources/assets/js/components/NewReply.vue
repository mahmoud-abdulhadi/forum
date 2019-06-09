<template>

         	<div>

         		<div v-if="signedIn">
	                <h4>Reply:</h4>
	             
	                   
	                    <div class="form-group">
	                        <textarea name="body" 
	                        	id="body" 
	                        	rows="6" 
	                        	 placeholder="It's Good day to say cool things!!"
	                        	  v-model="body"
	                        	  required>
	                            
	                        </textarea>
	                    </div>

	                    <div class="form-group">
	                        
	                        <button class="btn btn-primary"
	                        	 @click="addReply">Reply</button>
	                    </div>
                  </div>

                  <p class="text-center" v-else>
                  	Please <a href="/login">Sign In</a> to participate in this discussion.
                  	
                  </p>


           

		</div>
</template>

<script>
	import 'jquery.caret' ; 

	import 'at.js';

	export default {
		data(){


			return {

				body : ''
			}; 
		},
		computed : {


		},
		mounted(){

			$('#body').atwho({
		    at: "@",
		    delay: 1000,
		    callbacks: {
				    
				    remoteFilter: function(query, callback) {
				      	
				    
				    	$.getJSON('/api/users',{name:query}, function(users){

				    	callback(users);
				    	});
				     
				    }
  			}
		});

		},
		methods : {

			addReply(){

				axios.post(location.pathname + '/replies',{body : this.body})
					.catch(error => {

						flash(error.response.data,'danger');
					})
					.then(({data}) => {

						this.body = '';

						flash('Reply Submitted Successfully!!');

						this.$emit('created',data);

					});


			}



		}


	}
</script>

<style>
	.btn-primary{

		border-radius: 0px ; 
	}
</style>