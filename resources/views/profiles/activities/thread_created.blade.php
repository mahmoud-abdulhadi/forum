@component('profiles.activities.activity')

	@slot('heading')
		{{$profileUser->name}} Published <a href="{{$activity->subject->path()}}"><strong>{{$activity->subject->title}}</strong></a>.

	@endslot 


	@slot('body')
		{{$activity->subject->body}}
	@endslot

@endcomponent


