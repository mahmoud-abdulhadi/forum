<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">



    <style>
        

        textarea {


            padding : 5px ;
        }

        .level { 
                display : flex;

                align-items: center ;
         }

         .level-item {

            margin-right: 1em ; 
         }
         .flex { 

                flex : 1;
          }
          .mr-1 {

            margin-right: 1em ; 
          }

          .ml-a{

            margin-left: auto;
          }

          [v-cloak] {

            display : none ;
          }

          .btn-liked{

            background : #FF8484;
          }
    </style>
   
     <script>
      
      window.App = {!!
          json_encode([
            'csrfToken' => csrf_token(),
            'user' => Auth::user(),
            'signedIn' => Auth::check()
            ])

        !!}; 
    </script>

    @yield('styles')
     @yield('head')
    
</head>
<body style="padding-bottom:50px">
    <div id="app">
        
        @include('layouts.nav')
        @yield('content')
        <flash message="{{session('flash')}}"></flash>
    </div>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>

    @yield('scripts')
   
</body>
</html>
