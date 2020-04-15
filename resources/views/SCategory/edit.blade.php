@extends('Layouts.form-body')
<?php $title = 'Data Kategori Steam' ?>

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
            <form action="{{ action("SCategoryController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Nama Kategori</label>
                <input type="text" name="scategory_name" value="{{ $data->scategory_name }}" class="form-control" placeholder="Nama Kategori">
              </div>
              <div class="form-group">
                <label for="detail">Detail</label>
                <textarea class="form-control" rows="2" placeholder="Detail" name="scategory_detail">{{ $data->scategory_detail }}</textarea>
              </div>
              <div class="form-group">
                <label>Select</label>
                <select name='scategory_type' class="form-control">
                    <option value="Mobil">Mobil</option>
                    <option value="Motor">Motor</option>
                </select>
                </div>
              <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" name="scategory_price" value="{{ $data->scategory_price }}" class="form-control" id="scategory_price" placeholder="Harga">
              </div>
              @if(Perm::can(['steamKategori_simpan']))
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
              @endif
              @if($data->id)
                @if(Perm::can(['steamKategori_simpan']))
                <a href="{{action('SCategoryController@getEdit')}}" class="btn btn-sm btn-success" >
                  <i class="fa fa-plus fa-fw"></i>&nbsp;Tambah Baru
                </a>
                @endif
                @if(Perm::can(['steamKategori_hapus']))
                <a href="#" class="btn btn-sm btn-danger float-right" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('SCategoryController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('SCategoryController@index') }}">
                  <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
                @endif
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
              <input type="text" class="form-control" value="{{ $data->scategory_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->scategory_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->scategory_modified_at))
            <div class="col-12">
              <label>Diubah Oleh</label>
              <input type="text" class="form-control" value="{{ $data->scategory_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->scategory_modified_at)->format('d-M-Y')}}" readonly>
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
    $('[type=number]').setupMask(0);
  })
</script>
@endsection