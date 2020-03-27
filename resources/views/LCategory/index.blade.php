@extends('Layouts.lists-body')

<?php $title = 'Kategori Laundry' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Kategory Laundry</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('LCategoryController@getEdit') }}" class="btn btn-sm btn-success" type="button">
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
            <th>Nama Kategori</th>
            <th>Hari Pengerjaan</th>
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
      ajax: '{{ action("LCategoryController@getGrid") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'lcategory_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('LCategoryController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.lcategory_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'lcategory_days',
            searchText: true
        },
        { 
            data: 'lcategory_price',
            searchText: true
        },
        { 
            data: 'lcategory_created_at',
            searchText: true
        },
        { 
            data: 'lcategory_created_by',
            searchText: true
        },
        { 
            data: 'lcategory_modified_at',
            searchText: true
        },
        { 
            data: 'lcategory_modified_by',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection