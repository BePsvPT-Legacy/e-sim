<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">

    <title>E-Sim</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    @stack('styles')
    <link rel="stylesheet" href="{{ asset(app()->isLocal() ? 'css/app.css' : mix('css/app.css')) }}">
  </head>
  <body>
    @include('headers.index')

    <main class="container">
      @yield('main')
    </main>

    @include('footers.index')

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" defer></script>
    @stack('scripts')
    <script src="{{ asset(app()->isLocal() ? 'js/app.js' : mix('js/app.js')) }}" defer></script>
  </body>
</html>
