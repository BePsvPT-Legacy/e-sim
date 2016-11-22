<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Esim Tools</title>
        {{ HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css') }}
        {{ HTML::style('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap-theme.min.css') }}
        {{ HTML::script('http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js') }}
        {{ HTML::script('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js') }}
        <style>
            body {
                padding-top: 70px;
                /* Required padding for .navbar-fixed-top. Remove if using .navbar-static-top. Change if height of navigation changes. */
            }
        </style>
    </head>
    <body>
        <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
            <div class="container">
                <div class="navbar-header">
                    {{ HTML::linkRoute('index', 'Esim Taiwan', [], ['class' => 'navbar-brand']) }}
                </div>
                <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                    <ul class="nav navbar-nav">
                        <li class="dropdown">
                            <a href="{{ URL::route('index') }}" id="navigation" class="dropdown-toggle" data-target="#" data-toggle="dropdown" aria-haspopup="true" role="button" aria-expanded="false">
                                伺服器
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu" aria-labelledby="navigation">
                                <li>{{ HTML::link('secura', 'Primera') }}</li>
                                <li>{{ HTML::link('secura', 'Secura') }}</li>
                                <li>{{ HTML::link('secura', 'Suna') }}</li>
                            </ul>
                        </li>
                        <li>{{ HTML::linkRoute('battle.index', '戰場', ['server' => Route::getCurrentRoute()->getParameter('server')]) }}</li>
                    </ul>
                </div>
            </div>
            <script>$('.dropdown-toggle').dropdown()</script>
        </nav>
        <div class="container">
@yield('main')
        </div>
        <blockquote class="text-center" style="width: 100%;">
            <footer>Web Created by：Freedom / Copyright © 2015</footer>
        </blockquote>
    </body>
</html>