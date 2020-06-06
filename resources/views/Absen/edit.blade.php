@extends('Layouts.form-body')
<?php $title = 'Laundry Absen' ;
  $readOnly = isset($data->id) ? 'readonly' : '';
  $disabled = isset($data->id) ? 'disabled' : '';
?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#"> {{ trans('fields.laundryabsen') }} </a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data' }}</li>
@endsection

@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
    <div class="card">
      <div class="card-body pd-lg-25">
        <div class="row align-items-sm-end">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("LAbsenController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <fieldset class="form-fieldset">
                <legend>{{ trans('fields.catatan') }}</legend>
                <div class="form-group">
                  <label for="nama">{{ trans('fields.ket') }}</label>
                  <div class="wd-md-80p">
                  <textarea class="form-control" rows="2" placeholder="Detail" name="labsen_detail" {!! $readOnly !!}>{{ $data->labsen_detail }}</textarea>
                  </div>
                </div>
              </fieldset>
              <fieldset class="form-fieldset">
                <legend>{{ trans('fields.absen') }}</legend>
                <div class="row row-sm mg-b-10">
                @foreach($data->employeeList as $emp)
                <?php
                  $active = in_array($emp->employee_id, $data->hadir);
                  $selected = $active ? 'checked="checked"' : null;
                ?>
                <div class="col-sm-4">
                  <div class="custom-control custom-switch">
                    <input type="checkbox" name="employee[]" class="custom-control-input" value="{{$emp->employee_id}}" id="{{$emp->employee_id}}" {!! $selected !!} {!! $disabled !!}>
                    <label class="custom-control-label" for="{{$emp->employee_id}}">{{$emp->employee_name}}</label>
                  </div>
                  </div>
                @endforeach
                </div>
              </fieldset>
              <br>
              @if(empty($data->id) && Perm::can(['labsen_simpan']))
                <button type="submit" class="btn btn-sm btn-primary">{{ trans('fields.simpan') }}</button>
              @endif
              @if($data->id)
                <!-- @if(Perm::can(['labsen_hapus']))
                <a href="#" class="btn btn-sm btn-danger float-right" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('LAbsenController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('LAbsenController@index') }}">
                  <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
                @endif -->
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
            <label>{{ trans('fields.createdBy') }}</label>
            <input type="text" class="form-control" value="{{ $data->labsen_created_by}}" readonly>
          </div>
          <div class="col-12">
            <label>{{ trans('fields.dibuatT') }}</label>
            <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->labsen_created_at)->format('d-M-Y')}}" readonly>
          </div>
          @if (!empty($data->labsen_modified_at))
          <div class="col-12">
            <label>{{ trans('fields.modifiedBy') }}</label>
            <input type="text" class="form-control" value="{{ $data->labsen_modified_by}}" readonly>
          </div>
          <div class="col-12">
            <label>{{ trans('fields.diubahT') }}</label>
            <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->labsen_modified_at)->format('d-M-Y')}}" readonly>
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
    
  });
</script>
@endsection