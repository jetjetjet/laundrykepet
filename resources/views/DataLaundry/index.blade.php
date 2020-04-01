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
            <select name="status" class="form-control filter" >
              <option value="All">Semua</option>
              <option value="Draft"> Draft</option>
              <option value="Diproses">Diproses</option>
              <option value="Selesai">Selesai</option>
              <option value="Diantar">Diantar</option>
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
          <th>No. Invoice</th>
          <th>Pelanggan</th>
          <!-- <th>Alamat</th> -->
          <th>Kontak</th>
          <th>Tgl. Selesai</th>
          <th>Delivery</th>
          <th>Lunas</th>
          <th>Status</th>
          <th>Tgl Dibuat</th>
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
            let link =  "{{ action('LaundryController@input') . '/' }}" + full.id ;
            return '<a href="' + link + '">' + full.laundry_invoice + '</a>';
          },
          searchText: true
        },
        { 
          data: 'laundry_customer_name',
          searchText: true
        },
        // { 
        //   data: 'laundry_customer_address',
        //   searchText: true
        // },
        { 
            data: 'laundry_customer_phone',
            searchText: true
        },
        { 
          data: 'laundry_est_date',
          width: '90px',
          render: function (data, type, full, meta){
            return moment(data).format('DD-MM-YYYY');
          },
          searchText: true
        },
        { 
          data: 'laundry_delivery',
          width: '40px',
          render: function(data, type, full, meta){
            return data === true ? '<span class="badge badge-primary">Ya</span>' : '<span class="badge badge-warning">Tidak</span>' ;
          },
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
      "lunas": 6,
      "status": 7
    };
  
    $(".filter").on("change", function(){
      var $this = $(this);
      var val = $this.val();
      var key = $this.attr("name");

      dt.columns(inputMapper[key] - 1).search(val).draw();
    });
  });
</script>
@endsection