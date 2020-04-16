@extends('Layouts.lists-body')

<?php $title = 'Absen Laundry' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Absen Laundry</a></li>
@endsection

@section('container')
<div class="row">
  @if(Perm::can(['labsen_tambah']))
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
    <a href="{{ action('LAbsenController@getEdit') }}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  @endif
  @if (!empty($error))  
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $error }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
  </div>
  <br />
  @endif
  <div class="col-12">
    <div class="card">
      <!-- <div class="card-header">
        <h3 class="card-title">DataTable with minimal features & hover style</h3>
      </div> -->
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th>Tgl Absen</th>
              <th>Dibuat Oleh</th>
              <th>Tgl Diubah</th>
              <th>Diubah Oleh</th>
            </tr>
          </thead>
        </table>
      </div>
    </div>
  </div>
</div>
@endsection

@section('list-js')
<script>
  $(document).ready(function (){
    let dt = $('#grid').DataTable({
      ajax: '{{ action("LAbsenController@getList") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'labsen_created_at',
          render: function (data, type, full, meta){
            let link =  "{{ action('LAbsenController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + moment(full.labsen_created_at).format('DD-MM-YYYY')  + '</a>';
          },
          searchText: true
        },
        { 
          data: 'labsen_created_by',
          searchText: true
        },
        { 
          data: 'labsen_modified_at',
          searchText: true
        },
        { 
          data: 'labsen_modified_by',
          searchText: true,
          render: function (data, type, full, meta){
            return data != null ? moment(data).format('DD-MM-YYYY') : '';
          },
        },
      ]
    });
  });
</script>
@endsection