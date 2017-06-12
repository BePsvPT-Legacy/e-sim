@extends('layouts.master')

@section('main')
  <div class="card alert alert-info" role="alert">
    <div class="card-block p-0">
      <ol class="pl-4">
        <li>
          <h3>@lang('Battle')</h3>

          <ul class="pl-4">
            @include('component', [
              'title' => 'Entire Battle',
              'template' => '/{server}/battle/{battle_id}',
              'url' => route('battle.entire', ['secura', 777]),
            ])

            @include('component', [
              'title' => 'Round',
              'template' => '/{server}/battle/{battle_id}/{round_id}',
              'url' => route('battle.round', ['secura', 777, 1]),
            ])

            @include('component', [
              'title' => 'Military Unit',
              'template' => '/{server}/battle/{battle_id}/mu',
              'url' => route('battle.mu', ['secura', 777]),
            ])

            @include('component', [
              'title' => 'Country',
              'template' => '/{server}/battle/{battle_id}/country',
              'url' => route('battle.country', ['secura', 777]),
            ])
          </ul>
        </li>
      </ol>
    </div>
  </div>
@endsection
