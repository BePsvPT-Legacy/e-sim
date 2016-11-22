@extends('layout.master')

@section('main')
    <div>
        <div class="well" style="max-width: 512px; margin: 0 auto 10px;">
            <blockquote>
                <p>最新戰役ID - {{ $latest_battle_id[0]->value }}</p>
            </blockquote>
        </div>
    </div>
@stop