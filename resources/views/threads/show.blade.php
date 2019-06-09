@extends('layouts.app')


@section('head')

 <link rel="stylesheet" href="/css/vendor/jquery.atwho.css">
@endsection

@section('styles')
    <style>
            a{
                color : black;
            }
            #body{

                resize: none ; 

                width : 100%;
            }

            .form-control {

                border-radius: 0px ; 
            }
    </style>

@endsection

@section('content')

 <thread-view :thread="{{$thread}}" inline-template>
    <div class="container">
        <div class="row">
            <div class="col-md-8">
                
                @include('threads._question')
                <replies 
                
                    @removed="repliesCount--"
                    
                    @added="repliesCount++"
                    ></replies>
                   
                

              
            </div>

            <div class="col-md-4">
                <div class="panel panel-primary">
                    

                    <div class="panel-body">
                        <p>This Thread was published {{$thread->created_at->diffForHumans()}}
                            By <a href="#">{{$thread->creator->name}}</a>
                        </p>
                            <p>Currently has 
                                <span v-text="repliesCount"></span> 
                                Comments
                            </p>

                        <subscribe-button v-show="signedIn" :active="{{ json_encode($thread->isSubscribedTo)}}"></subscribe-button>

                        <button class="btn" v-show="authorize('isAdmin')" @click="toggleLock" :class="locked ? 'btn-success' : 'btn-danger'" v-text="locked ? 'Unlock' : 'Lock'">Lock</button>
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</thread-view>
@endsection
