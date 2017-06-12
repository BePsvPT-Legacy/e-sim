<thead>
  <tr>
    <th
      rowspan="2"
      class="{{ is_route('battle.mu') ? 'hidden' : '' }}"
    >@lang(is_route('battle.country') ? 'Country' : 'Citizen')</th>

    <th
      rowspan="2"
      class="{{ is_route('battle.country') ? 'hidden' : '' }}"
    >@lang('Military Unit')</th>

    <th
      rowspan="2"
      class="hidden-md-down"
    >@lang('Team')</th>

    <th
      rowspan="2"
    >@lang('Damage')</th>

    <th
      colspan="6"
      class="hidden-sm-down"
    >@lang('Weapon Usage')</th>
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
