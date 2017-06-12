<li class="page-item {{ is_route($name) ? 'active' : '' }}">
  <a
    class="page-link"
    href="{{ route($name, [$battle->server, $battle->battle_id]) }}"
  >@lang($title)</a>
</li>
