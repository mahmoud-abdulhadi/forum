@extends('layouts.app')





@section('content')


	<div class="container">
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="page-header">

					<avatar-form :user="{{$profileUser}}"></avatar-form>
		
		</div>
		<div class="work">
			<h1>Activities</h1>
			@forelse($groupedActivities as $day => $activities)
					<h3 class="page-header">{{$day}}</h3>
					@foreach($activities as $activity)

						@if(view()->exists("profiles.activities.{$activity->type}"))
							@include("profiles.activities.{$activity->type}")
						@endif
					@endforeach
			@empty
					<p>There is no activity for this user yet.</p>
			@endforelse
		
		</div>
				
			</div>
		</div>
		
		

	</div>

@endsection