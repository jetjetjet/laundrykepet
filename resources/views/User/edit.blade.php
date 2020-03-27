@extends('Layouts.form-body')
<?php $title = 'Data User' ?>

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
            <form action="{{ action("UserController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="user_name" value="{{ $data->user_name }}" class="form-control" id="nama" placeholder="Username">
              </div>
              <div class="form-group" style="display : {{ !empty($data->id) ? 'none' : '' }}">
                <label for="nama">Password</label>
                <input type="text" name="user_password" class="form-control" id="user_password" placeholder="Nama Lengkap" {{ empty($data->id) ? 'required' : '' }} >
              </div>
              <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="user_full_name" value="{{  old('user_full_name', $data->user_full_name) }}" class="form-control" id="user_full_name" placeholder="Nama Lengkap">
              </div>
              <div class="form-group">
                <label for="kontak">Kontak</label>
                <input type="text" name="user_phone" value="{{ $data->user_phone }}" class="form-control" id="kontak" placeholder="Kontak Pelanggan">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" rows="2" placeholder="Alamat" name="user_address">{{ $data->user_address }}</textarea>
              </div>
              <button type="submit" class="btn btn-primary">Simpan</button>
              @if($data->id)
                <a href="#" id="delete" type="button" class="btn btn-danger">Hapus</a>
                <a href="#" id="changePassword" type="button" class="btn btn-success" style="position: absolute; right: 10px;">Ubah Password</a>
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
              <input type="text" class="form-control" value="{{ $data->user_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->user_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->user_modified_at))
            <div class="col-12">
              <label>Diubah Oleh</label>
              
              <input type="text" class="form-control" value="{{ $data->user_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->user_modified_at)->format('d-M-Y')}}" readonly>
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
          , '{{action("UserController@postDelete")}}'
          , $('#csid').val()
          , 'Hapus'
          , 'Delete'
          , $('#user_name').val())
      });

      $('#changePassword').click(function(){
        setTimeout(() => {
          let dd = $('#uiModalInstance').find('.modal-body');
                dd.append('<div class="form-group">');
                dd.append('<label for="nama">Password Baru</label>');
                dd.append('<input type="text" name="user_password" class="form-control" placeholder="Password Baru" >');
                dd.append('</div>');
        }, 100);
        modalPopup('Ubah Password'
          , '{{action("UserController@postChangePassword")}}'
          , $('#csid').val()
          , 'Ubah'
          , null
          , $('#user_name').val())
      });
      
    });
</script>
@endsection