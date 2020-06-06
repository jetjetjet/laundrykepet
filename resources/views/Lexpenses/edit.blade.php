@extends('Layouts.form-body')
<?php $title = 'Data Lexpenses' ?>

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
            <form action="{{ action("LexpensesController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">{{ trans('fields.name') }}</label>
                <input type="text" name="lexpenses_name" value="{{ $data->lexpenses_name }}" class="form-control" placeholder="Kategory Name">
                </div>
              <div class="form-group">
              <label for="nama">{{ trans('fields.detail') }}</label>
                <input type="text" name="lexpenses_detail" value="{{ $data->lexpenses_detail }}" class="form-control" placeholder="Kategory Detail">
                </div>
              <div class="form-group">
              <label for="nama">{{ trans('fields.price') }}</label>
                <input type="number" name="lexpenses_price" value="{{ $data->lexpenses_price }}" class="form-control" placeholder="Kategory Price">
              </div>
              @if(Perm::can(['pengeluaranLaundry_simpan']))
              <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
              @endif
              @if($data->id && Perm::can(['pengeluaranLaundry_hapus']))
                <a href="#" class="btn btn-sm btn-danger" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('LexpensesController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('LexpensesController@index') }}">
                  <i class="fa fa-trash fa-fw"></i>&nbsp;Hapus</a>
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
              <label>{{ trans('fields.createdBy') }}</label>
              <input type="text" class="form-control" value="{{ $data->lexpenses_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>{{ trans('fields.dibuatT') }}</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->lexpenses_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->lexpenses_modified_at))
            <div class="col-12">
              <label>{{ trans('fields.modifiedBy') }}</label>
              <input type="text" class="form-control" value="{{ $data->lexpenses_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>{{ trans('fields.diubahT') }}</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->lexpenses_modified_at)->format('d-M-Y')}}" readonly>
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
    
    })
</script>
@endsection