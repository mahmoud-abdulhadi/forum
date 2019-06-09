@extends('layouts.app')


@section('head')

<script src='https://www.google.com/recaptcha/api.js'></script>

@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading"><h2>Create A New Thread</h2></div>

                <div class="panel-body">
                    <form method ="POST" action="/threads">
                        {{csrf_field()}}
                        <div class="form-group">
                         <label for="channel_id">Channel</label>
                         <select  id="channel_id" name="channel_id" class="form-control" required>
                            <option>Choose One... </option>
                             @foreach($channels as $channel)

                                <option value="{{$channel->id}}" {{old('channel_id') == $channel->id? "selected" : ""}}>{{$channel->name}}</option>
                             @endforeach
                         </select>
                        </div>

                        <div class="form-group">
                         <label for="title">Title:</label>
                         <input type="text" id="title" name="title" class="form-control" value="{{old('title')}}" required />
                        </div>

                            <div class="form-group">
                                    <label for="body">Body:</label>
                                    <textarea class="form-control" name="body" id="body" cols="30" rows="10" required>{{old('body')}}</textarea>

                            </div>
                            <div class="form-group">
                                <div class="g-recaptcha" data-sitekey="6Ld4FmEUAAAAAE2vbb833f8hNCySw8lpzcLcl_af"></div>
                            </div>

                                <div class="form-group">
                                    <button id="submitBtn" type="submit" class="btn btn-primary">Publish</button>
                                </div>

                                @if(count($errors))

                                    <div class="alert alert-danger">
                                        
                                            <ul class="list-unstyled">
                                                
                                                @foreach($errors->all() as $error)
                                                    <li>{{$error}}</li>
                                                @endforeach
                                            </ul>

                                    </div>

                                @endif
                        


                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
