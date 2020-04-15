@extends('Layouts.lists-body')

<?php $title = 'Setting' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Setting</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('SettingController@getEdit')}}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Category</th>
            <th>Key</th>
            <th>Velue</th>
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
      ajax : '{{ action("SettingController@getGrid") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'setting_key',
          render: function (data, type, full, meta){
            let link =  "{{ action('SettingController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.setting_category + '</a>';
          },
          searchText: true
        },
        { 
            data: 'setting_key',
            searchText: true
        },
        { 
            data: 'setting_value',
            searchText: true
        },
        { 
            data: 'setting_created_at',
            searchText: true
        },
        { 
            data: 'setting_created_by',
            searchText: true
        },
        { 
            data: 'setting_modified_at',
            searchText: true
        },
        { 
            data: 'setting_modified_by',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection