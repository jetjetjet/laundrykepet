@extends('Layouts.lists-body')

<?php $title = 'Data Pinjaman' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Pinjaman</a></li>
@endsection

@section('container')
<div class="row">
  @if(Perm::can(['peminjam_tambah']))
  <nav class="navbar navbar-light bg-light">
    <div class="btn-group">
    <a href="{{ action('LoanController@getEdit') }}" class="btn btn-sm btn-success" type="button">
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
            <th>Nama Karyawan</th>
            <th>Jumlah</th>
            <th>Jangka Waktu</th>
            <th>Lunas</th>
            <th>Keterangan</th>
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
      ajax: '{{ action("LoanController@getLoanLists") }}',
      "processing": true,
      "serverSide": true,
      columns: [
        { 
          data: 'loan_name',
          render: function (data, type, full, meta){
            let link =  "{{ action('LoanController@getEdit') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.loan_name + '</a>';
          },
          searchText: true
        },
        { 
            data: 'loan_amount',
            searchText: true
        },
        { 
            data: 'loan_tenor',
            searchText: true
        },
        { 
            data: 'loan_paidoff',
            searchText: true
        },
        { 
            data: 'loan_detail',
            searchText: true
        },
        { 
            data: 'loan_created_at',
            searchText: true
        },
        { 
            data: 'loan_created_by',
            searchText: true
        },
        { 
            data: 'loan_modified_at',
            searchText: true
        },
        { 
            data: 'loan_modified_by',
            searchText: true
        },
      ]
    });
  });
</script>
@endsection