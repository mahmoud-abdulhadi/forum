@component('profiles.activities.activity')

	@slot('heading')
		<a href="{{$activity->subject->favoritable->path()}}">{{$profileUser->name}} Favorited A Reply.</a>

	@endslot 


	@slot('body')
		{{$activity->subject->favoritable->body}}
	@endslot

@endcomponent
