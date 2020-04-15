@extends('Layouts.form-body')
<?php $title = 'Peran' ?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Peran</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
    <div class="card">
      <div class="card-body pd-lg-25">
        <div class="row align-items-sm-end">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("RoleController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <fieldset class="form-fieldset">
                <legend>Peran Karyawan</legend>
                <div class="form-group">
                  <label for="nama">Nama Peran</label>
                  <div class="wd-md-80p">
                    <input type="text" name="role_name" value="{{ old('role_name', $data->role_name) }}" class="form-control" placeholder="Nama Peran">
                  </div>
                </div>
                <div class="form-group">
                  <label for="nama">Daftar Karyawan</label>
                  <div class="select2-purple">
                    <select class="form-control select2" name="user_id[]" multiple="multiple">
                      @foreach( $user as $key=>$u)
                      <?php
                        $userActive = in_array($u->id, $data->user_id);
                        $selectedUser = $userActive ? ' selected' : null;
                      ?>
                        <option value="{{$u->id}}" {!! $selectedUser !!}>{{$u->user_name}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </fieldset>
              <fieldset class="form-fieldset">
                <legend>Hak Akses</legend>
                <div class="row row-sm mg-b-10">
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
                @foreach (Perm::all() as $key=>$group)
                <div class="col-sm-4">
                  <label><b>{{ $group->module}}</b></label>
                  @foreach($group->actions as $act)
                    <?php
                      $permissionActive = in_array($act->raw, $data->role_permissions ? : []);
                      $checkedStr = $permissionActive ? 'checked="checked"' : null;
                    ?>
                    <div class="custom-control custom-switch">
                        <input type="checkbox" name="permissions[]" class="custom-control-input" value="{{$act->raw}}" id="{{$act->raw}}" {!! $checkedStr !!} >
                        <label class="custom-control-label" for="{{$act->raw}}">{{$act->value}}</label>
                    </div>
                  @endforeach
                  </div>
                @endforeach    
                </div>
              </fieldset>
              <br>
              @if(Perm::can(['peran_simpan']))
                <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
              @endif
              @if($data->id)
                @if(Perm::can(['peran_simpan']))
                <a href="{{action('RoleController@getEdit')}}" class="btn btn-sm btn-success" >
                  <i class="fa fa-plus fa-fw"></i>&nbsp;Tambah Baru
                </a>
                @endif
                @if(Perm::can(['peran_hapus']))
                <a href="#" class="btn btn-sm btn-danger float-right" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('RoleController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('RoleController@index') }}">
                  <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
                @endif
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
            <input type="text" class="form-control" value="{{ $data->role_created_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Dibuat Tgl</label>
            <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->role_created_at)->format('d-M-Y')}}" readonly>
          </div>
          @if (!empty($data->role_modified_at))
          <div class="col-12">
            <label>Diubah Oleh</label>
            <input type="text" class="form-control" value="{{ $data->role_modified_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Diubah Tgl</label>
            <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->role_modified_at)->format('d-M-Y')}}" readonly>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('.select2').select2({
          placeholder: 'Pilih',
          searchInputPlaceholder: 'Search options'
        });
    });
</script>
@endsection