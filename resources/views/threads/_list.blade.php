
             @forelse($threads as $thread)
                         <div class="panel panel-default">

                            <div class="panel-heading">
                                <div class="level">
                                    <img src="{{$thread->creator->avatar_path}}" alt="{{$thread->creator->name}}" width="40" height="40" style="border-radius: 50%" class="mr-1">
                                    <div class="flex">
                                        <h3> 
                                            <a href="{{$thread->path()}}">

                                                {{$thread->title}}
                                                @if(auth()->check() && $thread->hasUpdatesFor(auth()->user()))
                                                 
                                                    &nbsp;&nbsp;<span class="badge badge-pill badge-danger">Updated </span> 
                                                 @endif
                                            </a>
                                        </h3>
                                         <h5>Posted By :<a href="{{route('profile',$thread->creator)}}"><strong>{{$thread->creator->name}}</strong></a></h5>
                                    </div>
                                
                                <strong><a href="{{$thread->path()}}">{{$thread->replies_count}} {{str_plural('reply',$thread->replies_count)}}</a></strong>
                                </div>
                               

                                </div>
                       
                           
                               
                            <div class="panel-body">
                                <p>{{$thread->body}}</p>
                 
                             </div>

                             <div class="panel-footer">
                               {{$thread->visits}} Views
                             </div>
                        </div>
                      @empty
                             <p>There no relevant reults at this time.</p>                     
                  @endforelse
