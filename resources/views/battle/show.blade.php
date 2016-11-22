@extends('layout.master')

@section('main')
    <div>
        @if ((is_array($data) ? ( ! count($data)) : ( ! $data->count())))
            <div class="text-center">
                <h3 class="bg-warning text-danger">
                    伺服器目前尚未存取此戰役資料，請稍待 3~5 分鐘後再次查詢<br><br>
                    (目前僅提供近 100 場戰役資料查詢，如該戰役尚未結束，請於結束後再查詢)
                </h3>
            </div>
        @else
            <div class="center-block" style="width: 100%;">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Rank</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Citizen</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Military Unit</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Total Damage</th>
                            <th rowspan="2" class="text-center" style="vertical-align: middle;">Attacker Side / Defender Side</th>
                            <th colspan="6" class="text-center" style="vertical-align: middle;">Weapon Usage</th>
                        </tr>
                        <tr>
                            <th class="text-center" style="vertical-align: middle;">Q0</th>
                            <th class="text-center" style="vertical-align: middle;">Q1</th>
                            <th class="text-center" style="vertical-align: middle;">Q2</th>
                            <th class="text-center" style="vertical-align: middle;">Q3</th>
                            <th class="text-center" style="vertical-align: middle;">Q4</th>
                            <th class="text-center" style="vertical-align: middle;">Q5</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        @foreach ($data as $datum)
                            <tr>
                                <td>{{{ $i++ }}}</td>
                                @if ($mu)
                                    <td>-</td>
                                @else
                                    <td>{!! HTML::link('https://'.($server).'.e-sim.org/profile.html?id='.$datum['citizen'], ((is_null($datum['citizen_name'])) ? $datum['citizen'] : $datum['citizen_name']['name']), ['target' => '_blank']) !!}</td>
                                @endif
                                @if($datum['military_unit'] == 0)
                                    <td>-</td>
                                @else
                                    <td>{!! HTML::link('https://'.($server).'.e-sim.org/militaryUnit.html?id='.$datum['military_unit'], ((is_null($datum['military_unit_name'])) ? $datum['military_unit'] : $datum['military_unit_name']['name']), ['target' => '_blank']) !!}</td>
                                @endif
                                <td>{{ number_format($datum['0']['damage'] + $datum['1']['damage']) }}</td>
                                <td>{{ number_format($datum['0']['damage']).' / '.number_format($datum['1']['damage']) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][0] + $datum['1']['weapon'][0]) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][1] + $datum['1']['weapon'][1]) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][2] + $datum['1']['weapon'][2]) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][3] + $datum['1']['weapon'][3]) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][4] + $datum['1']['weapon'][4]) }}</td>
                                <td>{{ number_format($datum['0']['weapon'][5] + $datum['1']['weapon'][5]) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
@stop