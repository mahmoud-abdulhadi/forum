<template>
	
	<button   :class="classes" @click="toggle">

		<span class="glyphicon glyphicon-heart"></span>
		<span v-text="count"></span>	       				

	</button>


</template>

<script>
	
	export default { 
		props : ['reply'],
		data(){


			return {

				count: this.reply.favoritesCount,
				isActive: this.reply.isFavorited   
			};
		},
		computed :{

			classes(){

				return ['btn', this.isActive ? 'btn-liked' : 'btn-default']; 
			},
			endpoint(){

				return '/replies/'+ this.reply.id + '/favorites' ; 
			}

		},
		methods: {


			toggle(){

				this.isActive ? this.destroy() : this.create() ; 
				


			},

			destroy(){


					axios.delete(this.endpoint); 

					this.isActive = false ;

					this.count-- ; 
			},
			create(){

				//favor it 

					axios.post(this.endpoint);

					this.isActive = true ;

					this.count++ ;  
			}


		}

	}
</script>