<header>
  <nav class="navbar navbar-toggleable-md navbar-inverse bg-inverse">
    <div class="container">
      <button class="navbar-toggler navbar-toggler-right" type="button" data-toggle="collapse" data-target="#navbar-dropdown" aria-controls="navbar-dropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <a class="navbar-brand" href="{{ route('home') }}">E-Sim</a>

      <div class="collapse navbar-collapse" id="navbar-dropdown">
        <ul class="navbar-nav mr-auto"></ul>

        <ul class="navbar-nav">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="language-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <i class="fa fa-globe" aria-hidden="true"></i>
              <span class="sr-only">Language</span>
            </a>

            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="language-dropdown">
              <a
                class="dropdown-item"
                href="{{ request()->fullUrlWithQuery(['lng' => 'zh-TW']) }}"
              >繁體中文</a>

              <a
                class="dropdown-item"
                href="{{ request()->fullUrlWithQuery(['lng' => 'en']) }}"
              >English</a>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
