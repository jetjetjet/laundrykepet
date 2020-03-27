@extends('Layouts.lists-body')

<?php $title = 'Hak Akses' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Peran</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('RoleController@getEdit') }}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
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
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Nama Peran</th>
            <th>Detail</th>
            <th>Diubah Oleh</th>
            <th>Tgl Dibuat</th>
            <th>Diubah Oleh</th>
            <th>Tgl Diubah</th>
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
      ajax: '{{ action("RoleController@getRoleLists") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'role_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('RoleController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.role_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'role_detail',
            searchText: true
        },
        { 
            data: 'role_created_by',
            searchText: true
        },
        { 
            data: 'role_created_at',
            searchText: true
        },
        { 
            data: 'role_modified_by',
            searchText: true
        },
        { 
            data: 'role_modified_at',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection