@extends('Layouts.index')

@section('index-css')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css">
@endsection

@section('bread-master')
  @yield('breadNav')
@endsection

@section('main-body')
  @if (Session::get('successMessages'))
    <div class="alert alert-success alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-d-none="true">&times;</span></button>
          {{ Session::get('successMessages') }}
    </div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-d-none="true">&times;</span></button>
      {{$errors->first()}}
    </div>
  @endif
  @if (Session::get('errorMessages'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-d-none="true">&times;</span></button>
          {{ Session::get('errorMessages') }}
    </div>
  @endif

  <section class="content">
    <div class="container-fluid">
      @yield('container')
    </div>
  </section>
@endsection

@section('index-js')
<script src="{{ url('/') }}/plugins/chart.js/Chart.min.js"></script>
<script src="{{ url('/') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ url('/') }}/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js"></script>
<script src="{{ url('/') }}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>

  

</script>
  @yield('form-js')
@endsection