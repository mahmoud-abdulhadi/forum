@extends('layouts.app')

@section('styles')
    <style>
        
        .panel-search .panel-heading {

            background : #222;

            color :#FFF;
        }

        .panel-search .btn{

            border-radius: 0px;
        }


        .panel-search .form-control {

            border-radius: 0px;
        }
    </style>

@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">

                      
            @include('threads._list')                

                
            {{$threads->render()}}
        </div>
        <div class="col-md-4">
            <div class="panel panel-search">
                    <div class="panel-heading">
                        <h4>Search</h4>
                        
                    </div>
                    <div class="panel-body">
                        <form action="/threads/search" method="GET">
                         

                            <div class="form-group">
                                <input type="text" class="form-control" name="q" placeholder="Search Threads">
                            </div>

                            <div class="form-group">
                                <button class="btn btn-primary" type="submit">Search</button>
                            </div>


                        </form>
                    </div>
                </div>
            <div class="panel panel-success">
                    <div class="panel-heading">
                        <h4>Trending Threads</h4>
                        
                    </div>
                    <div class="panel-body">
                        <ul class="list-group">
                            @foreach($trends as $thread)
                                <li class="list-group-item">
                                    <a href="{{$thread->path}}">{{$thread->title}}</a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @if(count($trends))
            	<div class="panel panel-success">
            		<div class="panel-heading">
            			<h4>Trending Threads</h4>
            			
            		</div>
            		<div class="panel-body">
            			<ul class="list-group">
            				@foreach($trends as $thread)
    	        				<li class="list-group-item">
    	        					<a href="{{$thread->path}}">{{$thread->title}}</a>
    	        				</li>
            				@endforeach
            			</ul>
            		</div>
            	</div>
            @endif

        </div>
    </div>
</div>
@endsection
