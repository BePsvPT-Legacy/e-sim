@extends('layout.master')

@section('main')
    <div>
        @if (is_null($data) || ! $data->{'open'})
            <div class="text-center">
                <h3 class="bg-warning text-danger">
                    似乎發生了一點問題喔~<br>
                    刷新看看吧~
                </h3>
            </div>
        @else
            <div class="center-block" style="width: 100%;">
                <table class="table table-bordered table-hover text-center">
                    <tr>
                        <td></td>
                        <th class="text-center">進攻方</th>
                        <th class="text-center">防守方</th>
                    </tr>
                    <tr>
                        <th class="text-center">傷害</th>
                        <td>{{ $data->{'attackerScore'} }} ({{ $data->{'percentAttackers'} }} %)</td>
                        <td>{{ $data->{'defenderScore'} }} ({{ 100 - floatval($data->{'percentAttackers'}) }} %)</td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">攻擊人數</th>
                        <td>{{ $data->{'defendersOnline'} }} 人</td>
                        <td>{{ $data->{'attackersOnline'} }} 人</td>

                    </tr>
                    <tr>
                        <td>{!! preg_replace('/<div.*div>/', '', $data->{'defendersByCountries'}) !!}</td>
                        <td>{!! preg_replace('/<div.*div>/', '', $data->{'attackersByCountries'}) !!}</td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">觀戰人數</th>
                        <td colspan="2">{{ $data->{'spectatorsOnline'} }} 人</td>
                    </tr>
                    <tr>
                        <td colspan="2">{!! preg_replace('/<div.*div>/', '', $data->{'spectatorsByCountries'}) !!}</td>
                    </tr>
                    <tr>
                        <th rowspan="2" class="text-center" style="vertical-align: middle;">剩餘時間</th>
                        <td colspan="2">{{ gmdate("H:i:s", $data->{'remainingTimeInSeconds'}) }}</td>
                    </tr>
                </table>
            </div>
        @endif
    </div>
@stop