@extends('Layouts.index')

@section('index-css')
  <link rel="stylesheet" href="{{ url('/') }}/plugins/select2/css/select2.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/daterangepicker/daterangepicker.css">
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
  @if($errors->any())
    <div class="alert alert-danger alert-dismissible" role="alert">
      <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      {{$errors->first()}}
    </div>
  @endif
  @if (Session::get('errorMessages'))
    <div class="alert alert-danger alert-dismissible" role="alert">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          {{ Session::get('errorMessages') }}
    </div>
  @endif
  <div class="page-header">
  <div class="card card-default">
    <div class="row">
      <div class="col-md-12">
        <div class="card-body" style="padding: 0.80rem;">
          <div class="well well-sm btn-toolbar">
            <div class="btn-group">
              <button class="btn btn-sm btn-success" type="button" data-saveMode="">
              <span class="fa fa-save fa-fw"></span>&nbsp;Save</button>
            </div>
          </div>  
        </div>  
      </div> 
    </div>   
  </div>  
</div>  

  <section class="content">
    <div class="container-fluid">
      @yield('container')
    </div>
  </section>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modalTitle"></h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary modal-yes" id="modalButton">Simpan</button>
          <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
        </div>
      </div>
    </div>
  </div>

@endsection

@section('index-js')
<script src="{{ url('/') }}/plugins/moment/moment.min.js"></script>
<script src="{{ url('/') }}/plugins/select2/js/select2.full.min.js"></script>
<script src="{{ url('/') }}/plugins/daterangepicker/daterangepicker.js"></script>
<script src="{{ url('/') }}/plugins/bootstrap-switch/js/bootstrap-switch.min.js"></script>
<script>

  

</script>
  @yield('form-js')
@endsection