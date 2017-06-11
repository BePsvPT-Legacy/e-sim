@extends('layouts.master')

@section('main')
  <ul class="pagination {{ $battle->round < 11 ? 'pagination-lg' : '' }}" style="overflow-x: auto; white-space: nowrap;">
    <li class="page-item {{ Route::currentRouteNamed('battle.entire') ? 'active' : '' }}">
      <a
        class="page-link"
        href="{{ route('battle.entire', [$battle->server, $battle->battle_id]) }}"
      >@lang('Entire Battle')</a>
    </li>

    @foreach (range(1, $battle->round) as $round)
      <li class="page-item {{ (Route::currentRouteNamed('battle.round') && $round == Route::current()->parameter('round')) ? 'active' : '' }}">
        <a
          class="page-link"
          href="{{ route('battle.round', [$battle->server, $battle->battle_id, $round]) }}"
        >{{ $round }}</a>
      </li>
    @endforeach

    <li class="page-item {{ Route::currentRouteNamed('battle.mu') ? 'active' : '' }}">
      <a
        class="page-link"
        href="{{ route('battle.mu', [$battle->server, $battle->battle_id]) }}"
      >@lang('Military Unit')</a>
    </li>

    <li class="page-item {{ Route::currentRouteNamed('battle.country') ? 'active' : '' }}">
      <a
        class="page-link"
        href="{{ route('battle.country', [$battle->server, $battle->battle_id]) }}"
      >@lang('Country')</a>
    </li>
  </ul>

  <br>

  <table class="table table-bordered table-hover text-center data-table" cellspacing="0" data-order='[[3, "desc"]]'>
    <thead>
      <tr>
        <th
          rowspan="2"
          style="{{ Route::currentRouteNamed('battle.mu') ? 'display: none;' : '' }}"
        >@lang(Route::currentRouteNamed('battle.country') ? 'Country' : 'Citizen')</th>
        <th rowspan="2" style="{{ Route::currentRouteNamed('battle.country') ? 'display: none;' : '' }}">@lang('Military Unit')</th>
        <th rowspan="2" class="hidden-md-down">@lang('Team')</th>
        <th rowspan="2">@lang('Damage')</th>
        <th colspan="6" class="hidden-sm-down">@lang('Weapon Usage')</th>
      </tr>

      <tr class="hidden-sm-down">
        <th>Q0</th>
        <th>Q1</th>
        <th>Q2</th>
        <th>Q3</th>
        <th>Q4</th>
        <th>Q5</th>
      </tr>
    </thead>

    <tbody>
      @forelse ($fights as $fight)
        <tr>
          <td class="text-left" style="{{ Route::currentRouteNamed('battle.mu') ? 'display: none;' : '' }}">
            @if (! Route::currentRouteNamed('battle.mu'))
              <span
                class="flag-icon flag-icon-{{ $fight['citizenship']['code'] }}"
                data-toggle="tooltip"
                data-placement="top"
                title="@lang($fight['citizenship']['name'])"
              ></span>

              @if (Route::currentRouteNamed('battle.country'))
                <span
                  class="pl-1 break-all"
                >@lang($fight['citizenship']['name'])</span>
              @else
                <a
                  href="https://{{ $battle->server }}.e-sim.org/profile.html?id={{ $fight['citizen']['id'] }}"
                  target="_blank"
                  class="pl-1 break-all"
                >{{ $fight['citizen']['name'] ?? $fight['citizen']['id'] }}</a>
              @endif
            @endif
          </td>

          <td style="{{ Route::currentRouteNamed('battle.country') ? 'display: none;' : '' }}">
            @if (is_null($fight['military_unit']['id']) || Route::currentRouteNamed('battle.country'))
              <span>-</span>
            @else
              <a
                href="https://{{ $battle->server }}.e-sim.org/militaryUnit.html?id={{ $fight['military_unit']['id'] }}"
                target="_blank"
                class="break-all"
              >{{ $fight['military_unit']['name'] ?? $fight['military_unit']['id'] }}</a>
            @endif
          </td>

          <td class="hidden-md-down">
            @php ($division = ($fight['attacker']['damage'] ?: 1) / ($fight['defender']['damage'] ?: 1))
            @php ($division = ($division < 1) ? (1 / $division) : $division)

            @if ($fight['attacker']['damage'] && $fight['defender']['damage'] && $division <= 10)
              <span class="text-danger">
                <span class="sr-only">@lang('Chaos')</span>

                <i
                  class="fa fa-balance-scale"
                  aria-hidden="true"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="@lang('Chaos')"
                ></i>
              </span>
            @elseif ($fight['attacker']['damage'] > $fight['defender']['damage'])
              <span class="text-primary">
                <span class="sr-only">@lang('Attacker')</span>

                <i
                  class="fa fa-location-arrow"
                  aria-hidden="true"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="@lang('Attacker')"
                ></i>
              </span>
            @else
              <span class="text-success">
                <span class="sr-only">@lang('Defender')</span>

                <i
                  class="fa fa-shield"
                  aria-hidden="true"
                  data-toggle="tooltip"
                  data-placement="top"
                  title="@lang('Defender')"
                ></i>
              </span>
            @endif
          </td>

          <td>
            @unless ($fight['attacker']['damage'] && $fight['defender']['damage'])
              <span>{{ nf($fight['attacker']['damage'] + $fight['defender']['damage']) }}</span>
            @else
              <span
                class="text-danger"
                data-toggle="tooltip"
                data-placement="right"
                title="A: {{ nf($fight['attacker']['damage']) }} / D: {{ nf($fight['defender']['damage']) }}"
              >{{ nf($fight['attacker']['damage'] + $fight['defender']['damage']) }}</span>
            @endunless
          </td>

          @foreach(range(0, 5) as $key)
            <td
              class="hidden-sm-down"
            >{{ $fight['attacker']['weapon'][$key] + $fight['defender']['weapon'][$key] }}</td>
          @endforeach
        </tr>
      @empty
        <tr>
          <td colspan="10">No Data</td>
        </tr>
      @endforelse
    </tbody>
  </table>
@endsection
