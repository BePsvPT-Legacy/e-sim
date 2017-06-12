<tbody>
  @forelse ($fights as $fight)
    <tr>
      <td class="text-left {{ is_route('battle.mu') ? 'hidden' : '' }}">
        @if (is_route('battle.mu'))
          {{-- 軍團頁面不顯示任何東西 --}}
          <span>-</span>
        @else
          {{-- 國旗 --}}
          <span
            class="flag-icon flag-icon-{{ $fight['citizenship']['code'] }}"
            data-toggle="tooltip"
            title="@lang($fight['citizenship']['name'])"
          ></span>

          @if (is_route('battle.country'))
            {{-- 國家頁面顯示國家名稱 --}}
            <span
              class="pl-1 break-all"
            >@lang($fight['citizenship']['name'])</span>
          @else
            {{-- 其他狀況顯示玩家名稱 --}}
            <a
              href="https://{{ $battle->server }}.e-sim.org/profile.html?id={{ $fight['citizen']['id'] }}"
              target="_blank"
              rel="noopener noreferrer"
              class="pl-1 break-all"
            >{{ $fight['citizen']['name'] ?? $fight['citizen']['id'] }}</a>
          @endif
        @endif
      </td>

      <td class="{{ is_route('battle.country') ? 'hidden' : '' }}">
        @if (is_null($fight['military_unit']['id']) || is_route('battle.country'))
          {{-- 無軍團資料或國家頁面不顯示任何東西 --}}
          <span>-</span>
        @else
          {{-- 其他狀況顯示軍團名稱 --}}
          <a
            href="https://{{ $battle->server }}.e-sim.org/militaryUnit.html?id={{ $fight['military_unit']['id'] }}"
            target="_blank"
            rel="noopener noreferrer"
            class="break-all"
          >{{ $fight['military_unit']['name'] ?? $fight['military_unit']['id'] }}</a>
        @endif
      </td>

      <td class="hidden-md-down">
        @php ($division = ($fight['attacker']['damage'] ?: 1) / ($fight['defender']['damage'] ?: 1))
        @php ($division = ($division < 1) ? (1 / $division) : $division)

        @if ($fight['attacker']['damage'] && $fight['defender']['damage'] && $division <= 10)
          @include('battles.table.team', ['color' => 'danger', 'team' => 'Chaos', 'icon' => 'balance-scale'])
        @elseif ($fight['attacker']['damage'] > $fight['defender']['damage'])
          @include('battles.table.team', ['color' => 'primary', 'team' => 'Attacker', 'icon' => 'location-arrow'])
        @else
          @include('battles.table.team', ['color' => 'success', 'team' => 'Defender', 'icon' => 'shield'])
        @endif
      </td>

      <td>
        @unless ($fight['attacker']['damage'] && $fight['defender']['damage'])
          <span>{{ nf($fight['damage']) }}</span>
        @else
          <span
            class="text-danger"
            data-toggle="tooltip"
            title="A: {{ nf($fight['attacker']['damage']) }} / D: {{ nf($fight['defender']['damage']) }}"
          >{{ nf($fight['damage']) }}</span>
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
      <td colspan="10">@lang('No Data')</td>
    </tr>
  @endforelse
</tbody>
