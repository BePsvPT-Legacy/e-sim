@extends('layouts.master')

@section('main')
  @include('battles.pagination.index')

  <table
    class="table table-bordered table-hover text-center data-table"
    cellspacing="0"
    data-order='[[3, "desc"]]'
  >
    @include('battles.table.head')
    @include('battles.table.body')
  </table>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="{{ asset('css/flag-icon.min.css') }}">
@endpush

@push('scripts')
<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" defer></script>
<script src="https://cdn.datatables.net/1.10.15/js/dataTables.bootstrap4.min.js" defer></script>
@endpush
