@extends('Layouts.form-body')
<?php $title = 'Data Setting' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">User</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
      <div class="card">
        <div class="card-body pd-lg-25">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("SettingController@postEdit") }}" method="POST" autocomplete="off" enctype="multipart/form-data">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Kategori</label>
                <input type="text" name="setting_category" value="{{ $data->setting_category }}" class="form-control" placeholder="Nama Kategori" readonly>
                </div>
              <div class="form-group">
              <label for="nama">Key</label>
                <input type="text" name="setting_key" value="{{ $data->setting_key }}" class="form-control" placeholder="Nama key" readonly>
                </div>
              <div class="form-group">
              <label for="nama">Value</label>
                @if($data->setting_category == "Logo")
                <div class="input-group">
                  <div class="custom-file">
                    <input type="file" class="custom-file-input" id="image" name="image" required>
                    <label class="custom-file-label" for="image">Choose file</label>
                  </div>
                </div>
                @else
                  <input type="text" name="setting_value" value="{{ $data->setting_value }}" class="form-control" placeholder="Nama value">
                @endif
              </div>
              @if(Perm::can(['setting_simpan']))
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
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
              <input type="text" class="form-control" value="{{ $data->setting_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->setting_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->setting_modified_at))
            <div class="col-12">
              <label>Diubah Oleh</label>
              <input type="text" class="form-control" value="{{ $data->setting_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->setting_modified_at)->format('d-M-Y')}}" readonly>
            </div>
            @endif
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
@endsection


@section('form-js')
<script src="{{ url('/') }}/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
<script>
    $(document).ready(function (){
      bsCustomFileInput.init();
    })
</script>
@endsection