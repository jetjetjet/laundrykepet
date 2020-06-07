@extends('Layouts.index')

@section('index-css')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/datatables-bs4/css/dataTables.bootstrap4.css">
  
  <link rel="stylesheet" href="https://cdn.datatables.net/rowreorder/1.2.7/css/rowReorder.dataTables.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.5/css/responsive.dataTables.min.css">
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
  <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
  <script src="{{ url('/') }}/plugins/datatables-bs4/js/dataTables.bootstrap4.js"></script>
  
  <script src="https://cdn.datatables.net/rowreorder/1.2.7/js/dataTables.rowReorder.min.js"></script>
  <script src="https://cdn.datatables.net/responsive/2.2.5/js/dataTables.responsive.min.js"></script>
@yield('list-js')
@endsection