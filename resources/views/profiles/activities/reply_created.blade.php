@component('profiles.activities.activity')

	@slot('heading')
		{{$profileUser->name}} Replied to <a href="{{$activity->subject->thread->path()}}"><strong>{{$activity->subject->thread->title}}</strong></a>.

	@endslot 


	@slot('body')
		{{$activity->subject->body}}
	@endslot

@endcomponent
