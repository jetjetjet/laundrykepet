@extends('Layouts.lists-body')

<?php $title = 'Data Laundry' ?>
@section('css-page')
  <style> 
  .dataTables_filter {
    display: none;
  }
  </style>
@endsection

@section('breadNav')
  <li class="breadcrumb-item active"><a href="{{ url('/') }}">Data Laundry</a></li>
@endsection

@section('container')

<div class="col-sm-6">
<div class="card card-info">
  <div class="card-header">
    <h3 class="card-title">Filter</h3>
    <div class="card-tools">
      <button type="button" class="btn btn-tool" data-card-widget="collapse">
        <i class="fas fa-minus"></i>
      </button>
    </div>
  </div>
  <div class="card-body">
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="nama">Nomor Invoice</label>
          <div class="input-group input-group-sm">
            <input type="text" name="invoice" class="form-control filter" >
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group input-group-sm">
          <label for="nama">Nama Pelanggan</label>
          <div class="input-group input-group-sm">
            <input type="text" name="customer" class="form-control filter" >
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-sm-6">
        <div class="form-group">
          <label for="nama">Status Pembayaran</label>
          <div class="input-group input-group-sm">
            <select name="lunas" class="form-control filter" >
              <option value="All">Semua</option>
              <option value="Lunas">Sudah Lunas</option>
              <option value="Belum">Belum Lunas</option>
            </select>
          </div>
        </div>
      </div>
      <div class="col-sm-6">
        <div class="form-group">
          <label for="nama">Status</label>
          <div class="input-group input-group-sm">
            <select id="status" name="status" class="form-control filter" >
              <option value="All">Semua</option>
              <option value="Draft"> Draft</option>
              <option value="Diproses">Diproses</option>
              <option value="Selesai">Selesai</option>
              <option value="Diantar">Diantar</option>
              <option value="Diambil">Diambil</option>
            </select>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</div>
            
<div class="card">
  <div class="card-body">
    <table id="grid" class="table table-condensed table-striped table-bordered table-hover" cellspacing="0" width="100%">
      <thead>
        <tr>
          <th>{{ trans('fields.no') ." ". trans('fields.invoice')}}</th>
          <th>{{trans('fields.customer')}}</th>
          <th>{{trans('fields.qty')}}</th>
          <th>{{trans('fields.total')}}</th>
          <th>{{trans('fields.paidoff')}}</th>
          <th>{{trans('fields.status')}}</th>
          <th>{{trans('fields.createdAt')}}</th>
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
      ajax: '{{ action("DataLaundryController@getLists") }}',
      processing: true,
      serverSide: true,
      responsive: true,
      columns: [
        { 
          data: 'laundry_invoice',
          width: '120px',
          render: function (data, type, full, meta){
            let link =  "{{ action('LaundryController@view') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.laundry_invoice + '</a>';
          },
          searchText: true
        },
        { 
          data: 'laundry_customer_name',
          searchText: true
        },
        { 
          data: 'ldetail_qty',
          width: '40px',
          searchText: true
        },
        { 
          data: 'ldetail_total',
          width: '40px',
          searchText: true
        },
        { 
          data: 'laundry_paidoff',
          width: '40px',
          render: function(data, type, full, meta){
            return data === true ? '<span class="badge badge-primary">Lunas</span>' : '<span class="badge badge-danger">Belum</span>' ;
          },
          searchText: true
        },
        { 
          data: 'laundry_status',
          searchText: true
        },
        { 
          data: 'laundry_created_at',
          width: '80px',
          render: function (data, type, full, meta){
            return moment(data).format('DD-MM-YYYY');
          },
          searchText: true
        },
      ],
    });

    var inputMapper = {
      "invoice": 1,
      "customer": 2,
      "lunas": 4,
      "status": 5,
    };

    var url_string = window.location.href; //window.location.href
    var url = new URL(url_string);
    var status = url.searchParams.get("status");

    if(status){
      $('#status').val(status);
      $this = $('#status');
      trigger($this);
    }
  
    $(".filter").on("change", function(){
      trigger($(this));
    });

    function trigger($this){
      var val = $this.val();
      var key = $this.attr("name");

      dt.columns(inputMapper[key] - 1).search(val).draw();
    }
  });
</script>
@endsection