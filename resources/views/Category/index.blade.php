@extends('Layouts.lists-body')

<?php $title = 'Data Kategory' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Kategory</a></li>
@endsection
@section('container')
<div class="container pd-x-0">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('CategoryController@getEdit') }}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  <hr />
  @if (!empty($error))
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    {{ $error }}
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
  </div>
  <br />
  @endif
  <div class="row ">
    <table id="grid" class="table table-condensed table-striped table-bordered table-hover table-wordwrap" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>Nama Kategory</th>
          <th>Harga</th>
          <th>Tgl Dibuat</th>
          <th>Diubah Oleh</th>
          <th>Tgl Diubah</th>
          <th>Diubah Oleh</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
@endsection

@section('list-js')
<script>
    $(document).ready(function (){
        let dt = $('#grid').DataTable({
            ajax: '{{ action("CategoryController@getCategoryLists") }}',
            "processing": true,
            "serverSide": true,
            columns: [
                { 
                  data: 'category_name',
                  render: function (data, type, full, meta){
                    let link =  "{{ action('CategoryController@getEdit') . '/' }}" + full.id ;
                    return '<a href="' + link + '">' + full.category_name + '</a>';
                  },
                  searchText: true
                },
                { 
                    data: 'category_price',
                    searchText: true
                },
                { 
                    data: 'category_created_at',
                    searchText: true
                },
                { 
                    data: 'category_created_by',
                    searchText: true
                },
                { 
                    data: 'category_modified_at',
                    searchText: true
                },
                { 
                    data: 'category_modified_by',
                    searchText: true
                },
            ]
        });
        
    });
</script>
@endsection