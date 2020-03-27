@extends('Layouts.form-body')
<?php $title = 'Input Laundry' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Laundry</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')

<form action="{{ action("LaundryController@postEdit") }}" method="POST" autocomplete="off" >
  <div class="row">
    <div class="col-lg-8 col-xl-9">
      <div class="card">
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
                <select class="form-control" name="laundry_customer_id"></select>
                <div class="input-group-append">
                  <button type="button" id="tmbhCust" class="btn btn-info btn-flat">Tambah Baru</button>
                </div>
              </div>
            </div>
            <div class="form-group">
              <label>Tgl Pengembalian</label>
              <div class="input-group input-group-sm">
                <input type="text" name="laundry_est_date" value="{{ $data->laundry_est_date }}" class="form-control" readonly />
              </div>
            </div>
            <div class="form-group">
              <label>Antar ke alamat</label>
              <div class="custom-control custom-switch">
                <input type="checkbox" class="custom-control-input" name="laundry_delivery" id="laundry_delivery">
                <label class="custom-control-label" id="deliv" for="laundry_delivery">Tidak</label>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    @if(!empty($data->id))
    <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
      <div class="card">
        <div class="card-footer pd-20">
          <div class="row">
            <div class="input-group input-group-sm">
              <label>Dibuat Oleh</label>
              <input type="text" class="form-control" value="{{ $data->lcategory_created_by}}" readonly>
            </div>
            <div class="input-group input-group-sm">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->lcategory_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->lcategory_modified_at))
            <div class="input-group input-group-sm">
              <label>Diubah Oleh</label>
              <input type="text" class="form-control" value="{{ $data->lcategory_modified_by}}" readonly>
            </div>
            <div class="input-group input-group-sm">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->lcategory_modified_at)->format('d-M-Y')}}" readonly>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endif
  </div>
  <div class="row">
    <div class="col-md-12" style="margin-bottom:8px;">
      <div class="float-right">
        <button type="button" class="btn btn-sm btn-success add-row" disabled>
          <span class="fa fa-plus fa-fw"></span>&nbsp; Tambah
        </button>
      </div>
    </div>
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
                <th>Jumlah/Kilo</th>
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
  </div>
  <div class="row">
    <div class="col-md-9 col-md-offset-8">
    </div>
    <div class="col-md-3 col-md-offset-8">
      <div class="input-group" style="margin-bottom:5px"> 
        <div class="input-group-prepend">
          <span class="input-group-text">Total</span>
        </div>
        <input type="text" class="form-control" id="total" readonly>
      </div>
      <div class="input-group" style="margin-bottom:5px"> 
        <div class="input-group-prepend">
          <span class="input-group-text">Bayar</span>
        </div>
        <input type="number" class="form-control" name="laundry_paidoff">
      </div>
      <div class="input-group" style="margin-bottom:5px"> 
        <div class="input-group-prepend">
          <span class="input-group-text">Selisih</span>
        </div>
        <input type="number" class="form-control" id="diff" readonly>
      </div>
    </div>
  </div>
</form>

<div id="declinePopUp" style="display:none;">
  <div class="form-horizontal">
    <div class="form-group">
      <label for="nama">Nama</label>
      <input type="hidden"  name="modal" value="1"  class="form-control">
      <input type="text"  name="customer_name" placeholder="Nama Pelanggan" class="form-control">
    </div>
    <div class="form-group">
      <label for="kontak">Kontak</label>
      <input type="text" name="customer_phone" class="form-control" id="kontak" placeholder="Kontak Pelanggan">
    </div>
    <div class="form-group">
      <label for="alamat">Alamat</label>
      <textarea class="form-control" rows="2" placeholder="Alamat" name="customer_address"></textarea>
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
    var $targetContainer = $('#detailLaundry');
    setupTableGrid($targetContainer);
    setupTotal();

    $('[name=laundry_customer_id]').on('change', function() {
      let selected = $(this).children("option:selected").val();
      alert(selected)
      if(selected != null){
        $('.add-row').prop('disabled', false);
      } else {
        $('.add-row').prop('disabled', true);
      }
    });

    $('[data-saveMode]').click(function (){
        $('form').submit();
    });

    //cari cust
    inputSearch('[name=laundry_customer_id]', '{{ action("CustomersController@searchCustomer") }}', 'resolve', function(item) {
      return {
        text: item.customer_name,
        id: item.id
      }
    });

    //DatePicker
    $('[name=laundry_est_date]').daterangepicker({
      singleDatePicker: true,
      showDropdowns: true,
      locale: {
        format: 'DD-MM-YYYY'
      }
    });

    //Modal Customer
    $('#tmbhCust').on('click', function() {
      var modal = showPopupForm(
      $(this),
      { btnType: 'primary', keepOpen: true },
      'Tambah Data Pelanggan',
      $('#declinePopUp'),
      $(this).attr('data-submit-url'),
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

    $('input[name="laundry_delivery"]').click(function(){
      if($('input[name="laundry_delivery"]').is(':checked')){
        $('#deliv').text('Ya');
      } else {
        $('#deliv').text('Tidak');
      }
    })
  });

  function calculate()
  {
    
  }

  function setupTableGrid($targetContainer)
  {
    // Setups add grid.
    $targetContainer.registerAddRow($('.row-template'), $('.add-row'));
    $targetContainer.on('row-added', function (e, $row){
      setupDetailLaundry($row);
    });
  }
    
  function setupDetailLaundry($targetContainer)
  {
    inputSearch(
      $targetContainer.find('[name^=dtl][name$="[ldetail_lcategory_name]"]'), 
      '{{ action("LCategoryController@getDropDownList") }}',
      '350px',function(item) {
      return {
        text: item.lcategory_name,
        id: item.id,
        price: item.lcategory_price
      }
    });

    $targetContainer.find('[name^=dtl][name$="[ldetail_lcategory_name]"]').on('select2:select', function (e) {
      var cB = e.params.data;
      $targetContainer.find('[name^=dtl][name$="[price]"]').val(cB.price);
      
      setupPrice();
      //console.log(data);
    });
  }

  function inputSearch(inputId, urlSearch, width, callBack)
  {
    let input = $(inputId);
    input.select2({
      placeholder: 'Cari...',
      width: width,
      ajax: {
        url: urlSearch,
        dataType: 'json',
        delay: 250,
        processResults: function (data) {
          return {
            results:  $.map(data, function (item) {
              return callBack(item)
            })
          };
        },
        cache: false
      }
    })
  }
    
  function setupPrice()
  {
    var $targetContainer = $('#detailLaundry');
    var price = $targetContainer.find('[name^=dtl][name$="[price]"]');
    var qty = $targetContainer.find('[name^=dtl][name$="[ldetail_qty]"]');
    var total = $targetContainer.find('[name^=dtl][name$="[ldetail_price]"]');
    setTimeout(() => {
      qty.focusout(function(e){
        alert(Number(qty.val()) * Number(price.val()));
        total.val(Number(qty.val()) * Number(price.val()));
      });
    }, 1000);
  }
</script>
@endsection