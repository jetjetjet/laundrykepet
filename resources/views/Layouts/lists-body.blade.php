@extends('Layouts.index')

@section('index-css')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  @yield('css-page')
@endsection

@section('bread-master')
  @yield('breadNav')
@endsection

@section('main-body')
  @if (Session::get('successMessages'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('successMessages') }}
    </div>
  @endif
  @if (Session::get('errorMessages'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('errorMessages') }}
    </div>
  @endif
  @if (!empty($error))  
  <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ $error }}
    </div>
  @endif
  <div class="container-fluid">
    @yield('container')
  </div>
@endsection

@section('index-js')
  <script src="{{ url('/') }}/plugins/datatables/jquery.dataTables.js"></script>
  <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
@yield('list-js')
@endsection