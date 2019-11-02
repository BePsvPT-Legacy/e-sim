@extends('layouts.master')

@section('main')
  <div class="row">
    @foreach ($equipments as $equipment)
      <div class="col-12 col-sm-6 col-md-4" style="margin-bottom: 2rem;">
        <div class="card">
          <div class="card-header card-warning text-white">
            <span>#{{ $equipment->equipment_id }}</span>
            <span>{{ $equipment->slot }} {{ $equipment->quality }}</span>
          </div>

          <ul class="list-group list-group-flush">
            @foreach ($equipment->parameters as $parameter)
              <li class="list-group-item">
                <small>{{ $parameter->type }}</small>
                <small class="pl-1">by</small>
                <small class="pl-1">{{ $parameter->value }}</small>
                @if ($parameter->percentage)
                  <small>%</small>
                @endif
              </li>
            @endforeach
          </ul>
        </div>
      </div>
    @endforeach
  </div>
@endsection
