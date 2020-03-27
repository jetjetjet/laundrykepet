@extends('Layouts.form-body')
<?php $title = 'Data Pelanggan'; ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Pelanggan</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection
@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
    <div class="card">
      <div class="card-body pd-lg-25">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("CustomersController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" id="customer_name" name="customer_name" value="{{ $data->customer_name }}" class="form-control" id="nama" placeholder="Nama Pelanggan">
              </div>
              <div class="form-group">
                <label for="kontak">Kontak</label>
                <input type="text" name="customer_phone" value="{{ $data->customer_phone }}" class="form-control" id="kontak" placeholder="Kontak Pelanggan">
              </div>
              <div class="form-group form-sm">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" rows="2" placeholder="Alamat" name="customer_address">{{ $data->customer_address }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary">Simpan</button>
              @if($data->id)
                <a href="#" id="delete" type="button" class="btn btn-danger">Hapus</a>
              @endif
            </form>
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
            <input type="text" class="form-control form-control-sm" value="{{ $data->customer_created_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Dibuat Tgl</label>
            <input type="text" class="form-control form-control-sm" value="{{ \carbon\carbon::parse($data->customer_created_at)->format('d-M-Y')}}" readonly>
          </div>
          @if (!empty($data->customer_modified_at))
          <div class="col-12">
            <label>Diubah Oleh</label>
            
            <input type="text" class="form-control form-control-sm" value="{{ $data->customer_modified_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Diubah Tgl</label>
            <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->customer_modified_at)->format('d-M-Y')}}" readonly>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  </div>
  @endif
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('#delete').click(function(){
        modalPopup('Hapus Data'
          , '{{action("CustomersController@postDelete")}}'
          , $('#csid').val()
          , 'Hapus'
          , 'Delete'
          , $('#customer_name').val())
      });
    });
</script>
@endsection