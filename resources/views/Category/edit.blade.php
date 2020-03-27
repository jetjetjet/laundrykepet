@extends('Layouts.form-body')
<?php $title = 'Data Kategori' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Kategori</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')
<div class="col-lg-8 col-xl-9">
    <div class="card">
      <div class="card-body pd-lg-25">
        <div class="row align-items-sm-end">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("CategoryController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label>Nama Kategori</label>
                <input type="text" name="category_name" value="{{ $data->category_name }}" class="form-control" id="category_name" placeholder="Nama Kategori">
              </div>
              <div class="form-group">
                <label>Detail Kategori</label>
                <textarea class="form-control" rows="2"  name="category_detail">{{ $data->category_detail }}</textarea>
              </div>
              <div class="form-group">
                <label>Harga</label>
                <input type="number" name="category_price" value="{{ $data->category_price }}" class="form-control" id="category_price" placeholder="Harga">
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
  </div>
  @if(!empty($data->id))
  <div class="col-md-6 col-lg-4 col-xl-3 mg-t-10 mg-lg-t-0">
    <div class="card">
      <div class="card-footer pd-20">
        <div class="row">
          <div class="col-12">
            <label>Dibuat Oleh</label>
            <input type="text" class="form-control" value="{{ $data->category_created_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Dibuat Tgl</label>
            <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->user_created_at)->format('d-M-Y')}}" readonly>
          </div>
          @if (!empty($data->category_modified_at))
          <div class="col-12">
            <label>Diubah Oleh</label>
            
            <input type="text" class="form-control" value="{{ $data->category_modified_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Diubah Tgl</label>
            <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->user_modified_at)->format('d-M-Y')}}" readonly>
          </div>
          @endif
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
          , '{{action("CategoryController@postDelete")}}'
          , $('#csid').val()
          , 'Hapus'
          , 'Delete'
          , $('#category_name').val())
      });
      
    });
</script>
@endsection