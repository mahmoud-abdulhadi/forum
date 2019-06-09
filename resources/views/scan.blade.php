	@extends('layouts.app')


@section('styles')
<style>
.ais-refinement-list__count{
	margin-left:10px;
	padding:8px;
	color : white;
	background : #333;

	border-radius : 25%;

}
.ais-refinement-list__item{

	margin : 10px;
}
</style>

@endsection

@section('content')

	 <div class="container">

	 	<ais-index
	     app-id="{{config('scout.algolia.id')}}"
	     api-key="{{config('scout.algolia.key')}}"
	     index-name="threads"
	   >
	     <ais-search-box></ais-search-box>
	     <div class="row">
	     	<div class="col-md-3">
	     		<ais-refinement-list attribute-name="channel.name"></ais-refinement-list>
	     	</div>
	     <div class="col-md-9">
	     <ais-results>
	       <template slot-scope="{ result }">
	         <p>
	         	<a :href="result.path">
	          	 <ais-highlight :result="result" attribute-name="title"></ais-highlight>
	           </a>
	         </p>
	       </template>
	     </ais-results>
	     </div>
	     </div>
	   </ais-index>
	  
	</div>


@endsection