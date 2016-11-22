@extends('layout.master')

@section('main')
    <div>
    @if($empty == true)
        <div class="text-center">
            <h2 class="bg-warning text-danger">不存在的戰役編號或回合</h2>
        </div>
    @else
        <div class="center-block" style="width: 100%;">
            <table class="table table-bordered table-hover text-center">
                <thead>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">#</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">公民</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">國籍</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">軍團</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">總傷害</th>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">攻擊/防守</th>
                        <th colspan="6" class="text-center" style="vertical-align: middle;">武器消耗</th>
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
                @foreach($battle as $each)
                    <tr>
                        <td>{{{ $i++ }}}</td>
                    @if($is_mu)
                        <td>-</td>
                    @else
                        <td>{{ HTML::link('https://secura.e-sim.org/profile.html?id='.$each['citizen'], $each['citizen'], ['target' => '_blank']) }}</td>
                    @endif
                        <td>-</td>
                        <td>{{{ ($each['military_unit'] != 0) ? $each['military_unit'] : '-' }}}</td>
                        <td>{{{ number_format($each['0']['damage'] + $each['1']['damage']) }}}</td>
                        <td>{{{ number_format($each['0']['damage']).' / '.number_format($each['1']['damage']) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][0] + $each['1']['weapon'][0]) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][1] + $each['1']['weapon'][1]) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][2] + $each['1']['weapon'][2]) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][3] + $each['1']['weapon'][3]) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][4] + $each['1']['weapon'][4]) }}}</td>
                        <td>{{{ number_format($each['0']['weapon'][5] + $each['1']['weapon'][5]) }}}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    @endif
    </div>
@stop