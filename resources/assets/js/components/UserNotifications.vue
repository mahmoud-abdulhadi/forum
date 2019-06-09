<template>

		<li class="dropdown">
			
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<span class="fa fa-bell">  <span class="badge badge-dark" v-if="notifications.length" v-text="notifications.length"></span></span>
			</a>

			<ul class="dropdown-menu">
				<li v-for="notification in notifications">
					<a :href="notification.data.link" v-text="notification.data.message" @click="markAsRead(notification)"></a>
				</li>
			</ul>
		</li>



</template>


<script>
	

	export default { 
		data(){


			return {

				notifications : false
			};
		},
		created(){

			axios.get("/profiles/" + window.App.user.name +"/notifications")
				.then(response => this.notifications = response.data );


		},
		methods : {
			///profiles/" . $user->name."/notifications/".$user->unreadNotifications->first()->id

			markAsRead(notification){

				axios.delete('/profiles/'+ window.App.user.name +'/notifications/' + notification.id);
			}
		}


	}
</script>