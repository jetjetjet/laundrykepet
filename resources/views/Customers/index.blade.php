@extends('Layouts.lists-body')

<?php $title = 'Data Karyawan' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Pelanggan</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
    <a href="{{ action('CustomersController@getEdit') }}" class="btn btn-sm btn-success" type="button">
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
      <!-- <div class="card-header">
        <h3 class="card-title">DataTable with minimal features & hover style</h3>
      </div> -->
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Nama Pelanggan</th>
            <th>Alamat</th>
            <th>Kontak</th>
            <th>Tgl Dibuat</th>
            <th>Diubah Oleh</th>
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
      ajax: '{{ action("CustomersController@getCustomerLists") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'customer_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('CustomersController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.customer_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'customer_address',
            searchText: true
        },
        { 
            data: 'customer_phone',
            searchText: true
        },
        { 
            data: 'customer_created_at',
            searchText: true
        },
        { 
            data: 'customer_cr',
            searchText: true
        },
        { 
            data: 'customer_modified_at',
            searchText: true
        },
        { 
            data: 'customer_mod',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection