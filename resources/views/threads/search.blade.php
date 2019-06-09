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
        .ais-highlight >em {

            background : #C7254E;

            color : #CCC;
        }

        .ais-refinement-list__value{

            margin-left : 5px;
        }

        .ais-refinement-list__item{

            margin : 5px 0 ; 
        }

        .ais-refinement-list__count{

            margin-left : 20px;
            padding : 5px;
            background : #333;
            color : white;
            border-radius : 50%;
        }

        .ais-refinement-list__checkbox{

            background : white;
        }
    </style>

@endsection

@section('content')
<div class="container">
      <ais-index
        app-id="{{config('scout.algolia.id')}}"
        api-key="{{config('scout.algolia.key')}}"
        index-name="threads"
        query="{{request('q')}}"
    >
    <div class="row">
        <div class="col-md-8">

                <ais-results>
        
                    <template scope="{result}">
                        
                        <li class="list-group-item">
                            <a :href="result.path" >
                                <ais-highlight :result="result"
                                    attribute-name="title"></ais-highlight>
                            </a>
                        </li>

                    </template>
                </ais-results>    
                     

                
        
        </div>
        <div class="col-md-4">

            <div class="panel panel-search">
                <div class="panel-heading">
                    Search
                </div>
                <div class="panel-body">
                    <ais-search-box >
                         <ais-input 
                         class="form-control"
                        placeholder="Search Threads"
                         autofocus="true">
                            </ais-input>
                    </ais-search-box>
                </div>
            </div>

            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h5>Filter By Channel</h5>
                </div>
                <div class="panel-body">
                    <ais-refinement-list attribute-name="channel.name" inline-template>
                        <ul class="list-group">
                            <li v-for="facet in facetValues" class="list-group-item">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" 
                                        value="facet.name"
                                         v-model="facet.isRefined"
                                         @change="toggleRefinement(facet)"><h5>@{{facet.name}}&nbsp;&nbsp;&nbsp;<span class="badge badge-secondary">@{{facet.count}}</span></label></h5>
                                </div>
                            </li>
                        </ul>
                    </ais-refinement-list>
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
