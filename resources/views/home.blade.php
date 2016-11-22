@extends('layout.master')

@section('main')
    <div class="center-block shadow home_page">
        <div class="heading">
            <blockquote>
                <h2>E-sim Taiwan</h2>
                <p>Support Server: Primera, Secura, Suna and Oriental</p>
            </blockquote>
        </div>
        <hr>
        <div>
            <div class="center-block" style="width: 80%;">
                <table class="table table-bordered table-hover text-center">
                    <thead>
                        <tr>
                            <th class="text-center">Server</th>
                            <th class="text-center">Latest Battle Id in Database</th>
                            <th class="text-center">Updated At</th>
                        </tr>
                    </thead>
                    @foreach ($data as $datum)
                        @if ('latest_battle_id' === $datum->name && 'oriental' !== $datum->server)
                            <tr>
                                <td>{{ ucfirst($datum->server) }}</td>
                                <td>{!! HTML::link('https://'.($datum->server).'.e-sim.org/battle.html?id='.($datum->content), $datum->content, ['target' => '_blank']) !!}</td>
                                <td>{{ $datum->updated_at }}</td>
                            </tr>
                        @endif
                    @endforeach
                </table>
            </div>
            <hr>
            <ol>
                <li>
                    <h3>Battle</h3>
                    <ul style="margin-right: 5%;">
                        <li>
                            <h4>Entire Battle</h4>
                            <pre class="shadow">{{ 'http://crux.coder.tw/freedom/esim/{server}/battle/{battle_id}' }}</pre>
                            <samp>e.g: {!! HTML::link('http://crux.coder.tw/freedom/esim/secura/battle/' . ($data[1]->content - 50)) !!}</samp>
                            <hr>
                        </li>
                        <li>
                            <h4>Specific Round</h4>
                            <pre class="shadow">{{ 'http://crux.coder.tw/freedom/esim/{server}/battle/{battle_id}/{round_id}' }}</pre>
                            <samp>e.g: {!! HTML::link('http://crux.coder.tw/freedom/esim/secura/battle/' . ($data[1]->content - 50) . '/2') !!}</samp>
                            <hr>
                        </li>
                        <li>
                            <h4>Military Unit</h4>
                            <pre class="shadow">{{ 'http://crux.coder.tw/freedom/esim/{server}/battle/{battle_id}/mu' }}</pre>
                            <samp>e.g: {!! HTML::link('http://crux.coder.tw/freedom/esim/secura/battle/' . ($data[1]->content - 50) . '/mu') !!}</samp>
                            <hr>
                        </li>
                        <li>
                            <h4>Live</h4>
                            <pre class="shadow">{{ 'http://crux.coder.tw/freedom/esim/{server}/live/{battle_id}' }}</pre>
                            <samp>e.g: {!! HTML::link('http://crux.coder.tw/freedom/esim/secura/live/' . $data[1]->content) !!}</samp>
                            <hr>
                        </li>
                    </ul>
                    <hr>
                </li>
            </ol>
        </div>
    </div>
@stop