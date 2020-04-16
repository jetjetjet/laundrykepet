@extends('Layouts.lists-body')

<?php $title = 'Pengeluaran Laundry' ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Pengeluaran Laundry</a></li>
@endsection

@section('container')
<div class="row">
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
      <a href="{{ action('LexpensesController@getEdit')}}" class="btn btn-sm btn-success" type="button">
      <span class="fa fa-plus fa-fw"></span>&nbsp;{{ trans('fields.new') }}</a>
    </div>
  </nav>
  
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <table id="grid" class="table table-bordered table-hover">
          <thead>
          <tr>
            <th>Name</th>
            <th>Price</th>
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
      ajax : '{{ action("LexpensesController@getGrid") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'lexpenses_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('LexpensesController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.lexpenses_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'lexpenses_price',
            searchText: true
        },
        { 
            data: 'lexpenses_created_at',
            searchText: true
        },
        { 
            data: 'lexpenses_created_by',
            searchText: true
        },
        { 
            data: 'lexpenses_modified_at',
            searchText: true
        },
        { 
            data: 'lexpenses_modified_by',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection