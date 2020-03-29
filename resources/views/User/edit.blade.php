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
              <div class="form-group">
                <label for="nama">Nama Lengkap</label>
                <input type="text" name="user_full_name" value="{{  old('user_full_name', $data->user_full_name) }}" class="form-control" id="user_full_name" placeholder="Nama Lengkap">
              </div>
              <div class="form-group" style="display : {{ !empty($data->id) ? 'none' : '' }}">
                <label for="nama">Password</label>
                <input type="text" name="user_password" class="form-control" id="user_password" placeholder="Password" {{ empty($data->id) ? 'required' : '' }} >
              </div>
              <div class="form-group">
                <label for="kontak">Kontak</label>
                <input type="text" name="user_phone" value="{{ $data->user_phone }}" class="form-control" id="kontak" placeholder="Nomor Kontak">
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" rows="2" placeholder="Alamat" name="user_address">{{ $data->user_address }}</textarea>
              </div>
              <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
              @if($data->id)
                <a href="#" class="btn btn-sm btn-danger" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('UserController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('UserController@index') }}">
                  <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
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

  <div id="passPopup" style="display:none;">
  <div class="form-horizontal">
    <div class="form-group required">
      <label for="nama">Password Baru</label>
      <input type="hidden"  name="modal" value="1"  class="form-control">
      <input type="text" name="user_password" class="form-control" placeholder="Password Baru" >
    </div>
  </div>
</div>
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('#changePassword').click(function(){
        var modal = showPopupForm(
        $(this),
        { btnType: 'primary', keepOpen: true },
        'Ubah Password User',
        $('#passPopup'),
        '{{ action("UserController@postChangePassword") }}' + '/' + $('#csid').val(),
        function ($form){
            return {
              user_password: $form.find('[name=user_password]').val(),
              modal: $form.find('[name=modal]').val()
            };
        },
        //callback
        function (data){
            toastr.success(data.successMessages)
        });
      });
    });
</script>
@endsection