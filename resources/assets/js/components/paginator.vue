<template>
	<ul class="pagination" v-if="shouldPaginate">
		<li v-show="prevUrl">
			<a href="" aria-label="Previous" rel="prev" @click.prevent="page--">
				<span aria-hidden="true">&laquo; Previous</span>
			</a>

		</li>
			

		<li v-show="nextUrl">
			<a href="" aria-label="Next" rel="next" @click.prevent="page++">
				<span aria-hidden="true"> Next &raquo;</span>
			</a>

		</li>

	</ul>


</template>



<script>
	
	export default{

		props : ['dataSet'],

		data(){



			return {
				page : 1,
				prevUrl : false, 
				nextUrl : false
			};
		},

		computed: {

			shouldPaginate(){

				return !! this.prevUrl ||  !! this.nextUrl ; 
			}

		},

		watch:{

			dataSet(){


				this.page = this.dataSet.current_page ; 

				this.nextUrl = this.dataSet.next_page_url ; 

				this.prevUrl = this.dataSet.prev_page_url ; 

			},
			page(){

				this.broadcast().updateUrl();
			}
		},
		methods:{

			broadcast(){

				this.$emit('changed',this.page);

				return this ; 
			},
			updateUrl()
			{


				history.pushState(null,null,'?page=' + this.page);
			}
		}


	}
</script>