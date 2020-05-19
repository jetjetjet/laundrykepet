@extends('Layouts.form-body')
<?php $title = 'Data Laundry' ?>

@section('breadNav')
<style>
  p{
    margin-bottom:0px;
  }
  .total td{
    padding: 0px;
  }
</style>
  <li class="breadcrumb-item active"><a href="#">Laundry</a></li>
  <li class="breadcrumb-item active" aria-current="page">Data Laundry</li>
@endsection

@section('container')
  <div class="card card-default">
    <div class="col-md-12">
      <div class="card-body" style="padding: 0.80rem;">
        <div class="btn-group">
          @if(empty($data->laundry_executed_at) && Perm::can(['laundry_simpan']) && empty($data->isEdit))
            <a href="{{action('LaundryController@generateReceipt') . '/' . $data->id }}" target="_blank" class="btn btn-sm btn-default" id="print">
            <span class="fa fa-print fa-fw"></span>&nbsp;{{ trans('fields.change')}}</a>
            &nbsp;
          @endif
          @if(!empty($data->id) && Perm::can(['laundry_cetak']))
            <a href="{{action('LaundryController@generateReceipt') . '/' . $data->id }}" target="_blank" class="btn btn-sm btn-default" id="print">
            <span class="fa fa-print fa-fw"></span>&nbsp;{{ trans('fields.print') }}</a>
            &nbsp;
          @endif
          <!-- @if($data->isFinish != 0 && Perm::can(['laundry_selesai']))
            <button id="selesai" class="btn btn-sm btn-success" type="button">
            <span class="fa fa-check fa-fw"></span>&nbsp;{{ trans('fields.finish')}}</button>
            &nbsp;
          @endif -->
          @if(!empty(($data->laundry_finished_at) && empty($data->laundry_delivered_at)) && $data->laundry_delivery && Perm::can(['laundry_antar']))
            <button class="btn btn-sm btn-warning" type="button" data-toggle="modal" data-target="#modalDelivery">
            <span class="fa fa-check fa-fw"></span>&nbsp;{{ trans('fields.delivery') }}</button>
            &nbsp;
          @endif
          @if(!empty(($data->laundry_finished_at) && empty($data->laundry_taken_at)) && !$data->laundry_delivery && Perm::can(['laundry_antar']))
            <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#modalPickup">
            <span class="fa fa-check fa-fw"></span>&nbsp;{{trans('fields.completed')}}</button>
            &nbsp;
          @endif
        </div>
        @if(!empty($data->id) && empty($data->isEdit) && Perm::can(['laundry_hapus']))
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
      <div class="invoice p-3 mb-3">
        <div class="row">
          <div class="col-12">
            <h4>
              <i class="fas fa-paper-plane"></i> {{$data->laundry_invoice}}
              <small class="float-right">{{trans('fields.transactionDate')}}: {{ \carbon\carbon::parse($data->laundry_created_at)->format('d-m-Y')}}</small>
            </h4>
          </div>
        </div>
        <div class="row invoice-info">
          <table class="table table-borderless total">
            <tr>
              <td width="25%">Pelanggan</td>
              <td>: {{$data->laundry_customer_name}}</td>
            </tr>
            <tr>
              <td>Telp/Hp</td>
              <td>: {{$data->customer_phone}}</td>
            </tr>
            <tr>
              <td>Alamat</td>
              <td>: {{$data->customer_address}}</td>
            </tr>
            <tr>
              <td>Delivery</td>
              <td>: {{ $data->laundry_delivery == true ? 'Ya' : 'Tidak'}}</td>
            </tr>
          </table>
        </div>
        <div class="row">
          <div class="col-12 table-responsive">
            <table class="table table-striped">
              <thead>
              <tr>
                <th>{{ trans('fields.category') }}</th>
                <th>{{ trans('fields.serviceDate') }}</th>
                <th>{{ trans('fields.pcs') }}/{{ trans('fields.type') }}</th>
                <th>{{ trans('fields.condition') }} {{ trans('fields.item') }}</th>
                <th>{{ trans('fields.price') }}</th>
                <th></th>
              </tr>
              </thead>
              <tbody>
                @foreach ($data->sub as $sub)
                  <tr>
                    <td>{{ $sub->ldetail_lcategory_name }}</td>
                    <td>{{ $sub->ldetail_start_date }} s/d {{ $sub->ldetail_end_date }}</td>
                    <td>{{ $sub->ldetail_qty }} ({{ $sub->ldetail_type }})</td>
                    <td>{{ $sub->ldetail_condition }}</td>
                    <td>{{ $sub->ldetail_total }}</td>
                    <td>
                      @if(empty($sub->ldetail_executed_at))
                        <a href="#" class="btn btn-xs btn-info" 
                          change-title="Proses Laundry"
                          change-action="{{action('LaundryController@postProses') . '/' . $data->id . '/' . $sub->id }}"
                          change-message="Apakah anda yakin untuk memproses item ini?"
                          change-success-url="{{ action('LaundryController@view') . '/' . $data->id }}">
                          <span class="fa fa-arrow-right fa-fw"></span></a>
                      @endif
                      @if(!empty($sub->ldetail_executed_at) && empty($sub->ldetail_finished_at))
                        <a href="#" class="btn btn-xs btn-success" 
                          change-title="Laundry Selesai"
                          change-action="{{action('LaundryController@postSelesai') . '/' . $data->id . '/' . $sub->id }}"
                          change-message="Apakah anda yakin untuk menyelesaikan item ini?"
                          change-success-url="{{ action('LaundryController@view') . '/' . $data->id }}">
                          <span class="fa fa-check fa-fw"></span></a>
                      @endif
                      @if(!empty($sub->ldetail_finished_at))
                        <a href="#" class="btn btn-xs btn-warning" 
                          change-title="Laundry Selesai"
                          change-action="{{action('LaundryController@postSelesai') . '/' . $data->id . '/' . $sub->id }}"
                          change-message="Apakah anda yakin untuk menyelesaikan item ini?"
                          change-success-url="{{ action('LaundryController@view') . '/' . $data->id }}">
                          <span class="fa fa-check fa-fw"></span></a>

                        <a href="#" class="btn btn-xs btn-info" 
                          change-title="Laundry Selesai"
                          change-action="{{action('LaundryController@postSelesai') . '/' . $data->id . '/' . $sub->id }}"
                          change-message="Apakah anda yakin untuk menyelesaikan item ini?"
                          change-success-url="{{ action('LaundryController@view') . '/' . $data->id }}">
                          <span class="fa fa-check fa-fw"></span></a>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
        <div class="row">
          <div class="col-6">
          </div>
          <div class="col-6 float-right">
            <div class="table-responsive">
              <table class="table table-borderless total">
                <tr>
                  <td>Total</td>
                  <td>: {{$data->total}}</td>
                </tr>
                <tr>
                  <td>Bayar</td>
                  <td>: {{number_format($data->laundry_paid)}}</td>
                </tr>
                <tr>
                  @if($data->diff != 0)
                    <td>Kurang Bayar</td>
                    <td>: {{ $data->diff }}</td>
                  @else
                    <td><strong>LUNAS</strong></td>
                  @endif
                </tr>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection

@section('form-js')
<script> 
  $(document).ready(function(){

     // let idDetail = this.getAttribute('data-dtl');
      // $('#dProses').setupPopupForm(
      // 'primary',
      // this.getAttribute('data-dtl') + 'Laundry akan diproses. lanjutkan?',
      // "{{ action('LaundryController@postUbahStatus', array('id' => old('id', $data->id), 'mode' => 'execute')) }}",
      // null,
      // { validateFormFirst: false });
    
    

  });
</script>
@endsection
