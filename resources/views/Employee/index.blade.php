@extends('Layouts.lists-body')

<?php $title = 'Karyawan' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Karyawan</a></li>
@endsection

@section('container')
<div class="row">
  @if(Perm::can(['karyawan_simpan']))
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('EmployeeController@getEdit') }}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  @endif
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Nama</th>
            <th>Kontak</th>
            <th>Tipe</th>
            <th>Tgl Dibuat</th>
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
      ajax: '{{ action("EmployeeController@getEmployeeLists") }}',
      "processing": true,
      "serverSide": false,
      columns: [
        { 
          data: 'employee_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('EmployeeController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.employee_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'employee_contact',
            searchText: true
        },
        { 
            data: 'employee_type',
            searchText: true
        },
        { 
            data: 'employee_created_at',
            searchText: true
        },
        { 
            data: 'employee_cr',
            searchText: true
        },
        { 
            data: 'employee_modified_at',
            searchText: true
        },
        { 
            data: 'employee_mod',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection