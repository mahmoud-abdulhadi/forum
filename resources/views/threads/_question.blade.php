<div class="panel panel-default" v-if="editing" v-cloak>
                    <div class="panel-heading">
                        
                        <div class="level">
                            

                            <span class="flex">
                               
                                
                                 <input type="text" class="form-control" v-model="form.title">
                             </span>
                           
                        </div>
                        

                    </div>

                    <div class="panel-body">
                     <textarea name="body" id="body" cols="30" rows="10" v-model="form.body"></textarea>
                    </div>
                    <div class="panel-footer">
                        <div class="level">
                            <button class="btn btn-info level-item" @click="editing=true" v-show="! editing">Edit</button>
                            <button class="btn btn-primary level-item" @click="update">Update</button>
                            <button class="btn btn-default level-item" @click="resetForm">Cancel</button>
                             @can('update',$thread)
                                 <form action="{{$thread->path()}}" method="POST" class="ml-a">
                                    {{csrf_field()}}
                                    {{method_field('DELETE')}}
                                    <button type="submit" class="btn btn-danger">Delete Thread</button>
                                </form>
                                @endcan
                        </div>
                    </div>
</div>
<div class="panel panel-default" v-else>
                    <div class="panel-heading">
                        
                        <div class="level">
                            

                            <span class="flex">
                                <img src="{{$thread->creator->avatar_path}}" alt="{{$thread->creator->name}}" width="40" height="40" style="border-radius: 50%" class="mr-1">
                                <a href="{{route('profile',$thread->creator)}}"><strong>{{$thread->creator->name}}</strong></a>&nbsp;Posted:&nbsp;
                                 <h3 v-text="title"></h3>
                             </span>
                        
                        </div>
                        

                    </div>

                    <div class="panel-body">
                     <p v-text="body"></p>
                    </div>
                    <div class="panel-footer" v-if="authorize('owns',thread)">
                        <button class="btn btn-info" @click="editing=true">Edit</button>
                    </div>
</div>