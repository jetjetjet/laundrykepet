@extends('Layouts.form-body')
<?php $title = 'Laporan Laundry';
  //$selType =  $data->type != null && $data
?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#"> {{ trans('fields.laporan') }} </a></li>
  <li class="breadcrumb-item active" aria-current="page">Laundry</li>
@endsection

@section('container')
<div class="row">
  <div class="col-12">
    <div class="card card-info">
      <div class="card-header">
        <h3 class="card-title">Laporan</h3>
        <div class="card-tools">
          <button type="button" class="btn btn-tool" data-card-widget="collapse">
            <i class="fas fa-minus"></i>
          </button>
        </div>
      </div>
      <div class="card-body">
        <form action="{{ action('ReportController@getLaundryReport') }}" class="form-horizontal" method="GET" autocomplete="off" novalidate>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="nama"> {{ trans('fields.tglawal') }} </label>
                <div class="input-group input-group-sm date" data-provide="datepicker" data-date-format="dd-mm-yyyy"  data-date-clear-btn="true">
                    <input type="text" name="startDate" value="{{ request('startDate') ? Carbon\Carbon::parse(request('startDate'))->format('d-m-Y') : date('1-M-Y') }}" class="form-control" 
                      autocomplete="off" readonly="readonly" required>
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>                                
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group input-group-sm">
                <label for="nama"> {{ trans('fields.tglakhir') }} </label>
                <div class="input-group input-group-sm date" data-provide="datepicker" data-date-format="dd-mm-yyyy"  data-date-clear-btn="true">
                    <input type="text" name="endDate" value="{{ request('endDate') ? Carbon\Carbon::parse(request('endDate'))->format('d-m-Y') : date('d-M-Y') }}" class="form-control" 
                      autocomplete="off" readonly="readonly" required>
                    <div class="input-group-append">
                      <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                    </div>                                
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-sm-3">
              <div class="form-group">
                <label for="nama">{{ trans('fields.statusPem') }} </label>
                <div class="input-group input-group-sm">
                  <select name="statusBayar" class="form-control filter" >
                    <option value="">Semua</option>
                    <option value="true" {!! request('statusBayar') == 'true' ? 'selected="selected"' : '' !!}>Sudah Lunas</option>
                    <option value="false" {!! request('statusBayar') == 'false' ? 'selected="selected"' : '' !!}>Belum Lunas</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="col-sm-3">
              <div class="form-group">
                <label for="nama">{{ trans('fields.statusTra') }} </label>
                <div class="input-group input-group-sm">
                  <select id="status" name="status" class="form-control filter" >
                    <option value="">Semua</option>
                    <option value="draft" {!! request('status') == 'draft' ? 'selected="selected"' : '' !!}> Draft</option>
                    <option value="proses" {!! request('status') == 'proses' ? 'selected="selected"' : '' !!}>Diproses</option>
                    <option value="selesai" {!! request('status') == 'selesai' ? 'selected="selected"' : '' !!}>Selesai</option>
                    <option value="antar" {!! request('status') == 'antar' ? 'selected="selected"' : '' !!}>Diantar</option>
                    <option value="ambil" {!! request('status') == 'ambil' ? 'selected="selected"' : '' !!}>Diambil</option>
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-9">
                        <div class="btn-group">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <span class="fa fa-search fa-fw"></span>&nbsp;{{ trans('fields.search') }}
                            </button>
                            <!-- <button id="print" type="button" class="btn btn-sm btn-default">
                                <span class="fa fa-print fa-fw"></span>&nbsp;{{ trans('fields.print') }}</a>
                            </button> -->
                        </div>
                    </div>
                    <div class="col-sm-1 show_and_hide"></div>
                </div>
            </div>
        </div>
        </form>
        <div class="row">
          <table class="table table-condensed table-striped table-bordered table-hover table-wordwrap" cellspacing="0" width="100%">
            <thead>
              <tr>
                <th class="text-center text-center-ps" width="5%">{{ trans('fields.no') }}</th>
                <th class="text-center text-center-ps" width="15%">{{trans('fields.invoice')}}</th>
                <th class="text-center text-center-ps" width="15%">{{trans('fields.sales')}}</th>
                <th class="text-center text-center-ps" width="20%">{{trans('fields.customerName')}}</th>
                <th class="text-center text-center-ps" width="5%">{{trans('fields.item')}}</th>
                <th class="text-center text-center-ps" width="10%">{{trans('fields.status')}} {{trans('fields.bayar')}}</th>
                <th class="text-center text-center-ps" width="5%">{{trans('fields.total')}}</th>
                <th class="text-center text-center-ps" width="5%">{{trans('fields.diff')}}</th>
                <th class="text-center text-center-ps" width="15%">{{trans('fields.date')}} {{trans('fields.transaction')}}</th>
                <th class="text-center text-center-ps" width="10%">{{trans('fields.status')}}</th>
              </tr>
            </thead>
            <tbody>
              <?php $rowNumber = 1; ?>
              @foreach($data as $row)
                <tr>
                  <td>{{ $rowNumber++ }} </td>
                  <td><a href="{{ action('LaundryController@input', array('id' => $row->id)) }}" target="_blank" title="{{ $row->laundry_invoice }}"> {{ $row->laundry_invoice }} </a></td>
                  <td>{{ $row->sales}}</td>
                  <td>{{ $row->customer_name}}</td>
                  <td>{{ $row->total_item}}</td>
                  <td>{{ $row->status_bayar}}</td>
                  <td>{{ $row->laundry_paid}}</td>
                  <td>{{ $row->selisih}}</td>
                  <td>{{ $row->laundry_created_at}}</td>
                  <td>{{ $row->status}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>

  <div id="deletePopup" style="display:none;">
    <div class="form-horizontal">
      <p> Anda akan menghapus data ini, lanjutkan? </p>
    </div>
  </div>
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('[type=number]').setupMask(0);
    });
</script>
@endsection