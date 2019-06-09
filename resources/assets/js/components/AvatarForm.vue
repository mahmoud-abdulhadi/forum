<template>
	<div>
		<div class="level">
			<img :src="avatar" alt="Avatar user" width="100" height="100" class="mr-1" />

			<h1 v-text="user.name"></h1>
		</div>
		
					<div class="mt-2">
					
						<form  v-if="authorize('owns',user , 'id')"  method="POST" enctype="multipart/form-data">
							
							<div class="form-group">
								<image-upload name="avatar" @loaded="onLoad"></image-upload>
								
							</div>
						
						</form>
					</div>
				
			
		</div>
</template>


<script>
	import ImageUpload from './ImageUpload.vue' ; 

	export default {
		props : ['user'],
		components : {ImageUpload},

		data(){

			return {

				avatar : this.user.avatar_path

			}
		},

		computed : {


			canUpdate(){

				return this.authorize(user => user.id === this.user.id)
			}

		},

		methods : {

			onLoad(avatar){

				
				this.avatar = avatar.src ; 
				//persist to the server 
				this.persist(avatar.file);
		
			},

			persist(avatar){

				let data = new FormData();

				data.append('avatar',avatar)
				axios.post(`/api/users/${this.user.name}/avatar`,data)
					.then(() => flash('Avatar Uploaded!'));
			}	
		}




	}
</script>


<style>
	
	.mt-2 { 

			margin-top: 2em;
	 }
</style>