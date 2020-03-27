@extends('Layouts.form-body')
<?php $title = 'Data Kategori Laundry' ?>

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
            <form action="{{ action("LCategoryController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Nama Kategori</label>
                <input type="text" name="lcategory_name" value="{{ $data->lcategory_name }}" class="form-control" placeholder="Nama Kategori">
              </div>
              <div class="form-group">
                <label for="detail">Kategori Detail</label>
                <textarea class="form-control" rows="2" placeholder="Detail" name="lcategory_detail">{{ $data->lcategory_detail }}</textarea>
              </div>
              <div class="form-group">
                <label for="nama">Hari Pengerjaan</label>
                <input type="number" name="lcategory_days" value="{{  old('lcategory_days', $data->lcategory_days) }}" class="form-control" id="lcategory_days" placeholder="Jumlah Hari Pengerjaan">
              </div>
              <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" name="lcategory_price" value="{{ $data->lcategory_price }}" class="form-control" id="lcategory_price" placeholder="Harga">
              </div>
              @if(Perm::can(['kategoriLaundry_simpan']))
                <button type="submit" class="btn btn-primary">Simpan</button>
              @endif
              @if($data->id && Perm::can(['kategoriLaundry_hapus']))
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
              <input type="text" class="form-control" value="{{ $data->lcategory_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->lcategory_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->lcategory_modified_at))
            <div class="col-12">
              <label>Diubah Oleh</label>
              <input type="text" class="form-control" value="{{ $data->lcategory_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->lcategory_modified_at)->format('d-M-Y')}}" readonly>
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
<script>
    $(document).ready(function (){
      $('#delete').click(function(){
        modalPopup('Hapus Data'
          , '{{action("LCategoryController@postDelete")}}'
          , $('#csid').val()
          , 'Hapus'
          , 'Delete'
          , $('[name=lcategory_name]').val())
      });
    });
</script>
@endsection