<li style="margin-bottom: 2rem;">
  <p class="lead" style="margin-bottom: .5rem;">@lang($title)</p>

  <div class="card">
    <div class="card-block break-all">
      <span>{{ route('home').$template }}</span>
    </div>
  </div>

  <p
    class="break-all"
    style="margin-top: .5rem;"
  >e.g: <a href="{{ $url }}">{{ $url }}</a></p>
</li>
