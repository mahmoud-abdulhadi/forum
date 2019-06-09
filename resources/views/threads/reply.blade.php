<reply :reply='{{$reply}}' inline-template v-cloak>
<div id="reply-{{$reply->id}}" class="panel panel-primary">
        <div class="panel-heading">
				<div class="level">
	        	<h4 class="flex">
	        		<a href="{{route('profile',$reply->owner)}}">{{$reply->owner->name}}</a> 
	        		said {{$reply->created_at->diffForHumans()}}</h4>

	        	<div>
					@if(auth()->check())
						<favorite :reply="{{$reply}}"></favorite>
					@endif
	        		{{--<form method="POST" action="/replies/{{$reply->id}}/favorites">
	        			{{csrf_field()}}
	        			<button type="submit" class="btn btn-default" {{$reply->isFavorited() ? "disabled" : ""}}>
	        				{{$reply->favorites_count}} {{str_plural('Like',$reply->favorites_count)}}
	        			</button>
	        			
	        		</form>--}}
	        	</div>

	        </div>

        </div>

        <div class="panel-body">

        	<div v-if="editing">
        		<div class="form-group">
        		<textarea class="form-control" style="resize: none;height:100px;" v-model="body"></textarea>
        		</div>
        		<button class="btn btn-primary btn-xs" @click="update">Update</button>
        		<button class="btn btn-xs btn-link" @click="editing = false">Cancel</button>
        		
        	</div>
        	<div v-else>
        		<p v-text="body">{{$reply->body}}</p>
        	</div>
         
        </div>

        @can('update',$reply)
	        <div class="panel-footer level">
	        	<button class="btn btn-xs mr-1" @click="editing=true">Edit</button>
	        	<button class="btn btn-danger btn-xs" @click="destroy">Delete</button>
	        	
	        

	        </div>
        @endcan

</div>
</reply>
