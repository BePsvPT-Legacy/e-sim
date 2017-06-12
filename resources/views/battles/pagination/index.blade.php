<ul
  class="pagination {{ $battle->round < 11 ? 'pagination-lg' : '' }}"
  style="overflow-x: auto; white-space: nowrap; margin-bottom: 2rem;"
>
  @include('battles.pagination.special', ['name' => 'battle.entire', 'title' => 'Entire Battle'])

  @foreach (range(1, $battle->round) as $round)
    <li class="page-item {{ ($round == Route::current()->parameter('round')) ? 'active' : '' }}">
      <a
        class="page-link"
        href="{{ route('battle.round', [$battle->server, $battle->battle_id, $round]) }}"
      >{{ $round }}</a>
    </li>
  @endforeach

  @include('battles.pagination.special', ['name' => 'battle.mu', 'title' => 'Military Unit'])
  @include('battles.pagination.special', ['name' => 'battle.country', 'title' => 'Country'])
</ul>
