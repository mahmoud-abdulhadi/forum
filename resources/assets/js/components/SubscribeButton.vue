<template>
	
	<button :class="classes" @click="subscribe">
		<span v-if="isActive">Subscribed</span>


		<span v-else>Subscribe</span>
	</button>
</template>

<script>
	export default{

		props:['active'],
		data(){


			return {

				isActive : this.active,
				
			};
		},
	
		computed:{

			classes(){


				return ['btn', this.isActive? 'btn-success': 'btn-default'] ; 
			},
			endpoint(){


				return location.pathname + '/subscriptions' ; 
			}


		},

		methods: {


			subscribe(){

				let requestType = this.isActive ? 'delete' : 'post' ;

				axios[requestType](this.endpoint); 

				this.isActive = ! this.isActive ; 

		
			}
		}


	}
</script>

<style>
	
	.btn {


		border-radius: 0px ; 
	}
</style>