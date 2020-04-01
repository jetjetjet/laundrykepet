@extends('Layouts.lists-body')

<?php $title = 'Kategori Steam' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Kategory Steam</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('SCategoryController@getEdit')}}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Nama Kategori</th>
            <th>Detail</th>
            <th>Tipe</th>
            <th>Harga</th>
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
      ajax : '{{ action("SCategoryController@getGrid") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'scategory_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('SCategoryController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.scategory_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'scategory_detail',
            searchText: true
        },
        { 
            data: 'scategory_type',
            searchText: true
        },
        { 
            data: 'scategory_price',
            searchText: true
        },
        { 
            data: 'scategory_created_at',
            searchText: true
        },
        { 
            data: 'scategory_created_by',
            searchText: true
        },
        { 
            data: 'scategory_modified_at',
            searchText: true
        },
        { 
            data: 'scategory_modified_by',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection