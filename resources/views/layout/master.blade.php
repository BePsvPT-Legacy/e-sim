<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>E-sim Taiwan</title>
        {!! HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css') !!}
        {!! HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap-theme.min.css') !!}
        {!! HTML::style(URL::asset('css/app.css')) !!}
        {!! HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js') !!}
        {!! HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js') !!}
        <link rel="icon" type="image/png" href="{!! URL::asset('favicon.png') !!}">
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    {!! HTML::linkRoute('home', 'E-sim Taiwan', [], ['class' => 'navbar-brand']) !!}
                </div>
            </div>
        </nav>
        <div class="container">
            @yield('main')
        </div>
        <footer class="text-center">
            Web Created by：Freedom / Copyright © 2015
        </footer>
    </body>
</html>