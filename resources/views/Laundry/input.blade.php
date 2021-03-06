@extends('Layouts.form-body')
<?php $title = 'Input Laundry' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Laundry</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')
<div class="card card-default">
    <div class="col-md-12">
      <div class="card-body" style="padding: 0.80rem;">
        <div class="btn-group">
          @if(empty($data->laundry_executed_at) && Perm::can(['laundry_simpan']))
            <button class="btn btn-sm btn-success" id="simpan" type="button" data-saveMode="" disabled>
            <span class="fa fa-save fa-fw"></span>&nbsp;Simpan</button>
            &nbsp;
          @endif
          @if(!empty($data->id) && Perm::can(['laundry_cetak']))
            <a href="{{action('LaundryController@generateReceipt') . '/' . $data->id }}" target="_blank" class="btn btn-sm btn-default" id="print">
            <span class="fa fa-print fa-fw"></span>&nbsp;Cetak</a>
            &nbsp;
          @endif
          @if(!empty($data->id) && empty($data->laundry_executed_at) && Perm::can(['laundry_ubahStatus']))
            <button id="proses" class="btn btn-sm btn-primary" type="button">
              <span class="fa fa-check fa-fw"></span>&nbsp;Proses</button>
            &nbsp;
          @endif
          @if(!empty($data->laundry_executed_at) && empty($data->laundry_finished_at) && Perm::can(['laundry_ubahStatus']))
            <button id="selesai" class="btn btn-sm btn-success" type="button">
            <span class="fa fa-check fa-fw"></span>&nbsp;Selesai</button>
            &nbsp;
          @endif
          @if(!empty(($data->laundry_finished_at) && empty($data->laundry_delivered_at)) && $data->laundry_delivery && Perm::can(['laundry_antar']))
            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#modalDelivery">
            <span class="fa fa-check fa-fw"></span>&nbsp;Antar</button>
            &nbsp;
          @endif
          @if(!empty(($data->laundry_finished_at) && empty($data->laundry_taken_at)) && !$data->laundry_delivery && Perm::can(['laundry_antar']))
            <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#modalPickup">
            <span class="fa fa-check fa-fw"></span>&nbsp;Pick-up</button>
            &nbsp;
          @endif
        </div>
        @if(!empty($data->id) && empty($data->laundry_executed_at) && Perm::can(['laundry_hapus']))
          <div class="float-right">
            <a href="#" class="btn btn-sm btn-danger" 
              delete-title="Konfirmasi Hapus Data Laundry"
              delete-action="{{action('LaundryController@postDelete') . '/' . $data->id }}"
              delete-message="Apakah anda yakin untuk menghapus data ini?"
              delete-success-url="{{ action('DataLaundryController@index') }}">
              <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
          </div>
        @endif
      </div>  
    </div> 
</div>  

<form id="mainForm" action="{{ action("LaundryController@postEdit") }}" method="POST" autocomplete="off" >
  <div class="row">
    <div class="col-lg-8 col-xl-9">
      <div class="row">
        <!-- Header -->
        <div class="card col-lg-12">
          <div class="card-body pd-lg-25">
            <div class="col-lg-12 col-xl-12">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label>Nomor Invoice</label>
                <div class="input-group input-group-sm">
                  <input type="text" name="laundry_invoice" value="{{ $data->laundry_invoice }}" class="form-control" readonly />
                </div>
              </div>
              <div class="form-group">
                <label>Nama Pelanggan</label>
                <div class="input-group input-group-sm">
                @if(empty($data->laundry_executed_at))
                  <select class="form-control" id="custSearch" name="laundry_customer_id">
                    @if($data->laundry_customer_id)
                      <option value="{{$data->laundry_customer_id}}" selected="selected">{{$data->laundry_customer_name}}</option>
                    @endif
                  </select>
                  @if(Perm::can(['pelanggan_tambah']))
                  <div class="input-group-append">
                    <button type="button" id="tmbhCust" class="btn btn-info btn-flat">Tambah Baru</button>
                  </div>
                  @endif
                @else
                  <input type="hidden" name="laundry_customer_id" value="{{$data->laundry_customer_id}}" class="form-control" readonly />
                  <input type="text" name="laundry_customer_name" value="{{$data->laundry_customer_name}}" class="form-control" readonly />
                @endif
                </div>
              </div>
              <div class="form-group">
                <label>Antar ke alamat</label>
                  <select class="form-control" id="tipe" name="laundry_delivery" {{!empty($data->laundry_executed_at) ? 'disabled' : '' }}>
                    <option value="true" {{ $data->laundry_delivery == true ? ' selected' : '' }}>Ya</option>
                    <option value="false" {{ $data->laundry_delivery == false ? ' selected' : '' }}>Tidak</option>
                  </select>
              </div>
            </div>
          </div>
        </div>
        <!-- Button Detail -->
        <div class="col-md-12" style="margin-bottom:8px;">
        @if(Perm::can(['laundry_simpan']) && empty($data->laundry_executed_at))
          <div class="float-right">
            <button type="button" class="btn btn-sm btn-success add-row" disabled>
              <span class="fa fa-plus fa-fw"></span>&nbsp; Tambah
            </button>
          </div>
        @endif
        </div>
        <!-- Detail -->
        <div class="col-md-12">
          <div class="card">
            <div class="card-header">
              <h3 class="card-title">Detail</h3>
            </div>
            <div class="card-body">
              <table id="detailLaundry" class="table table-condensed table-striped table-bordered table-hover table-wordwrap" cellspacing="0" width="100%">
                <thead>
                  <tr>
                    <th>Kategori</th>
                    <th>Tgl. Selesai</th>
                    <th>Berat/Satuan</th>
                    <th>Total</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($data->sub as $sub)
                    @include('Laundry.subRow', ['row' => $sub, 'rowIndex' => $loop->index])
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="col-md-9 col-md-offset-8">
        </div>
        <div class="col-md-3 col-md-offset-8">
          <div class="input-group" style="margin-bottom:5px"> 
            <div class="input-group-prepend">
              <span class="input-group-text">Total</span>
            </div>
            <input type="number" id="total" name="laundry_total"  class="form-control input-sm" readonly="readonly" />
          </div>
          <div class="input-group" style="margin-bottom:5px"> 
            <div class="input-group-prepend">
              <span class="input-group-text">DP</span>
            </div>
            <input type="number" class="form-control text-right" value="{{$data->laundry_paid}}" name="laundry_paid" id="byr" required {{ !empty($data->laundry_executed_at) ? 'readonly' : '' }}>
          </div>
          <div class="input-group" style="margin-bottom:5px"> 
            <div class="input-group-prepend">
              <span class="input-group-text">Selisih</span>
            </div>
            <input type="number" class="form-control text-right" id="diff" readonly>
          </div>
        </div>
      </div>
    </div>
    @if(!empty($data->id))
      <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
        <div class="card">
          <div class="card-footer pd-20">
            <div class="row">
              <div class="col-12">
                <label>Dibuat Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_created_by}}" readonly>
              </div>
              <div class="col-12">
                <label>Dibuat Tgl</label>
                <input type="text" class="form-control form-control-sm" value="{{ \carbon\carbon::parse($data->laundry_created_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @if (!empty($data->laundry_modified_at))
              <div class="col-12">
                <label>Diubah Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_modified_by}}" readonly>
              </div>
              <div class="col-12">
                <label>Diubah Tgl</label>
                <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->laundry_modified_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @endif
              @if(!empty($data->laundry_executed_at))
              <!-- <div class="col-12">
                <label>Diubah Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_modified_by}}" readonly>
              </div> -->
              <div class="col-12">
                <label>Diproses Tgl</label>
                <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->laundry_execute_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @endif
              @if(!empty($data->laundry_finished_at))
              <div class="col-12">
                <label>Diselesaikan Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_finished_by}}" readonly>
              </div>
              <div class="col-12">
                <label>Diselesaikan Tgl</label>
                <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->laundry_finished_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @endif
              @if(!empty($data->laundry_delivered_at))
              <div class="col-12">
                <label>Diantar Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_delivered_by}}" readonly>
              </div>
              <div class="col-12">
                <label>Diantar Tgl</label>
                <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->laundry_delivered_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @endif
              @if(!empty($data->laundry_taken_at))
              <div class="col-12">
                <label>Diambil Oleh</label>
                <input type="text" class="form-control form-control-sm" value="{{ $data->laundry_taken_by}}" readonly>
              </div>
              <div class="col-12">
                <label>Diambil Tgl</label>
                <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->laundry_taken_at)->format('d-M-Y H:m')}}" readonly>
              </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    @endif
  </div>
</form>

<div id="custPopup" style="display:none;">
  <div class="form-horizontal">
    <div class="form-group required">
      <label for="nama">Nama</label>
      <input type="hidden"  name="modal" value="1"  class="form-control">
      <input type="text"  name="customer_name" placeholder="Nama Pelanggan" class="form-control" required>
    </div>
    <div class="form-group required">
      <label for="kontak">Kontak</label>
      <input type="text" name="customer_phone" class="form-control" id="kontak" placeholder="Kontak Pelanggan" required>
    </div>
    <div class="form-group">
      <label for="alamat">Alamat</label>
      <textarea class="form-control" rows="2" placeholder="Alamat" name="customer_address"></textarea>
    </div>
  </div>
</div>

<div id="finishedPopup" class="d-none">
  <div class="form-horizontal">
    <div class="form-group required">
      <label for="nama">DP</label>
      <input type="number" value="{{$data->laundry_paid}}" id="dp" name="dp" class="form-control text-right popup-number" readonly>
    </div>
    <div class="form-group required">
      <label for="nama">Sisa Bayar</label>
      <input type="number" id="leftover" value="{{ isset($data->diff) ? $data->diff : '' }}" min="{{ isset($data->diff) ? $data->diff : '' }}" max="{{ isset($data->diff) ? $data->diff : '' }}" name="leftover" class="form-control text-right popup-number" required>
    </div>
  </div>
</div>

<div class="modal fade" id="modalDelivery" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ action('LaundryController@postDelivery', array('id' => old('id', $data->id))) }}"  method="POST" autocomplete="off">
        <div class="modal-header">
          <h6 class="modal-title" id="modalTitle">Delivery</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-d-none="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-horizontal">
            <div class="form-group required">
              <label>Karyawan Antar</label>
              <div class="input-group input-group">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                <select class="form-control" id="delivSearch"  name="laundry_delivered_id" {{ empty($data->laundry_delivered_by) && !empty($data->laundry_finished_by) ? "required" : ""}}>
                  @if($data->laundry_delivered_by)
                    <option value="{{$data->laundry_delivered_id}}" selected="selected">{{$data->laundry_delivered_by}}</option>
                  @endif
                </select>
              </div>
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" style="min-width: 75px;" class="btn btn-success btn-sm font-bold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="modal fade" id="modalPickup" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="{{ action('LaundryController@postPickup', array('id' => old('id', $data->id))) }}"  method="POST" autocomplete="off">
        <div class="modal-header">
          <h6 class="modal-title" id="modalTitle">Laundry Pick-up</h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-d-none="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          <div class="form-horizontal">
            <div class="form-group required">
              <label>Diambil Oleh</label>
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="text"  name="laundry_taken_by" class="form-control">
            </div>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm" data-dismiss="modal">Batal</button>
          <button type="submit" style="min-width: 75px;" class="btn btn-success btn-sm font-bold">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>

<table class="row-template invisible" >
    @include('Laundry.subRow')
</table>
@endsection

@section('form-js')
<script>
$(document).ready(function ()
  {
    @if($data->id)
      $('#simpan').prop('disabled', false);
      $('.add-row').prop('disabled', false);
    @endif
    
    $('[data-saveMode]').click(function (){
      $('#mainForm').validate();
      $('#mainForm').submit();
    });

    var $targetContainer = $('#detailLaundry');
    setupTableGrid($targetContainer);
    $('#total').setupMask(0);
    setupTotal();

    $('#byr').setupMask(0);
    $('#diff').setupMask(0);
    $('#byr').focusout(function(){
      calculate();
    })

    $('#custSearch').on('change', function() {
      let selected = $(this).children("option:selected").val();
      if(selected != null){
        $('#simpan').prop('disabled', false);
        $('.add-row').prop('disabled', false);
      } else {
        $('.add-row').prop('disabled', true);
        $('#simpan').prop('disabled', true);
      }
    });

    //cari cust
    inputSearch('#custSearch', '{{ action("CustomersController@searchCustomer") }}', 'resolve', function(item) {
      return {
        text: item.customer_name,
        id: item.id
      }
    });
    $('#custSearch').on('select2:select', function (e) {
      $('#custSearch').attr('data-has-changed', '1');
    });

    //cari karyawan antar
    inputSearch('#delivSearch', '{{ action("EmployeeController@searchEmployee") }}', '450px', function(item) {
      return {
        text: item.employee_name,
        id: item.id
      }
    });
    $('#tipe').on('change', function (e) {
      $('#tipe').attr('data-has-changed', '1');
    });

    $targetContainer.find('[type=number]').setupMask(0);

    $('#selesai').on('click', function(){    
      var modalFin = showPopupForm(
      $(this),
      { btnType: 'primary', keepOpen: true },
      'Laundry Selesai',
      $('#finishedPopup'),
      "{{ action('LaundryController@postUbahStatus', array('id' => old('id', $data->id), 'mode' => 'finish')) }}",
      function ($form){
          return {
            dp: $form.find('[name=dp]').val(),
            leftover: $form.find('[name=leftover]').val()
          };
      },
      //callback
      function (data){
        toastr.success(data.messages)
        setTimeout(() => {
          location.reload();
        }, 1500);
      });
    });

    $('#proses').setupPopupForm(
      'primary',
      'Laundry akan diproses. lanjutkan?',
      "{{ action('LaundryController@postUbahStatus', array('id' => old('id', $data->id), 'mode' => 'execute')) }}",
      null,
      { validateFormFirst: true });

    //Modal Customer
    $('#tmbhCust').on('click', function() {
      var modal = showPopupForm(
      $(this),
      { btnType: 'primary', keepOpen: true },
      'Tambah Data Pelanggan',
      $('#custPopup'),
      '{{ action("CustomersController@postEdit") }}',
      function ($form){
          return {
            customer_name: $form.find('[name=customer_name]').val(),
            customer_phone: $form.find('[name=customer_phone]').val(),
            customer_address: $form.find('[name=customer_address]').val(),
            modal: $form.find('[name=modal]').val()
          };
      },
      //callback
      function (data){
          toastr.success(data.messages)
      });
    });
  });

  function setupTotal()
  {
    let detailRows = $('#detailLaundry').find('[name^=dtl][name$="[ldetail_total]"]').closest('tr');
    let totalLaundry = 0;

    detailRows.each(function(){
      totalLaundry += parseFloat($(this).find('[name^=dtl][name$="[ldetail_total]"]').val());
    });

    $('#total').val(totalLaundry).trigger('requestUpdateMask');
    calculate();
  }

  function calculate()
  {
    let j1 = $('#total').val();
    let j2 = $('#byr').val();
    $('#diff').val(Number(j1) - Number(j2));
    $('#diff').trigger('requestUpdateMask');
  }

  function setupTableGrid($targetContainer)
  {
    // Setups add grid.
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
      let numRow = $row.find('[name^=dtl][name$="[ldetail_total]"]');
      numRow.setupMask(0);
      setupDetailLaundry($row);
    })
    .on('row-removing', function (e, $row){
      setTimeout(() => {
        setupTotal();
      }, );
    });
  }
    
  function setupDetailLaundry($targetContainer)
  {
    inputSearch(
      $targetContainer.find('[name^=dtl][name$="[ldetail_lcategory_id]"]'), 
      '{{ action("LCategoryController@getDropDownList") }}',
      '250px',function(item) {
      return {
        text: item.lcategory_name,
        id: item.id,
        price: item.lcategory_price,
        days: item.lcategory_days
      }
    });

    $targetContainer.find('[name^=dtl][name$="[ldetail_lcategory_id]"]').on('select2:select', function (e) {
      var cB = e.params.data;
      var endDate = setupDate(cB.days);
      $targetContainer.find('[name^=dtl][name$="[price]"]').val(cB.price);
      $targetContainer.find('[name^=dtl][name$="[ldetail_end_date]"]').val(endDate);
      $targetContainer.attr('data-has-changed', '1');
      
      setupPrice($targetContainer);
      //console.log(data);
    });
  }

  function setupDate(days)
  {
    var newDate = new Date();
    var date =  newDate.setDate(newDate.getDate() + days);
    return moment(date).format('DD-MM-YYYY');
  }
    
  function setupPrice($targetContainer)
  {
    var price = $targetContainer.find('[name^=dtl][name$="[price]"]');
    var qty = $targetContainer.find('[name^=dtl][name$="[ldetail_qty]"]');
    var total = $targetContainer.find('[name^=dtl][name$="[ldetail_total]"]');
    setTimeout(() => {
      qty.focusout(function(e){
        total.val(Number(qty.val()) * Number(price.val()));
        total.trigger('requestUpdateMask');
        setupTotal();
      });
    }, 1000);
  }
</script>
@endsection