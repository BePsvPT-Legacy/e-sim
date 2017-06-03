@extends('layouts.master')

@section('main')
  <div class="card alert alert-info" role="alert">
    <div class="card-block">
      <ol>
        <li>
          <h3>@lang('Battle')</h3>

          <ul>
            <li class="statistic-list">
              <p class="lead">@lang('Entire Battle')</p>

              <div class="card">
                <div class="card-block">
                  <span>{{ url('/') }}/{server}/battle/{battle_id}</span>
                </div>
              </div>

              <p class="example">e.g: <a href="{{ route('battle.entire', ['secura', 777]) }}">{{ route('battle.entire', ['secura', 777]) }}</a></p>
            </li>

            <li class="statistic-list">
              <p class="lead">@lang('Specific Round')</p>

              <div class="card">
                <div class="card-block">
                  <span>{{ url('/') }}/{server}/battle/{battle_id}/{round_id}</span>
                </div>
              </div>

              <p class="example">e.g: <a href="{{ route('battle.round', ['secura', 777, 1]) }}">{{ route('battle.round', ['secura', 777, 1]) }}</a></p>
            </li>

            <li class="statistic-list">
              <p class="lead">@lang('Military Unit')</p>

              <div class="card">
                <div class="card-block">
                  <span>{{ url('/') }}/{server}/battle/{battle_id}/mu</span>
                </div>
              </div>

              <p class="example">e.g: <a href="{{ route('battle.mu', ['secura', 777]) }}">{{ route('battle.mu', ['secura', 777]) }}</a></p>
            </li>
          </ul>
        </li>
      </ol>
    </div>
  </div>
@endsection
