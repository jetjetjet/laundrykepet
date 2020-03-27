@extends('Layouts.form-body')
<?php $title = 'Data Karyawan';
  //$selType =  $data->type != null && $data
?>

@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Karyawan</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection

@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
      <div class="card">
        <div class="card-body pd-lg-25">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("EmployeeController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                <label for="nama">Nama</label>
                <input type="text" name="employee_name" value="{{ $data->employee_name }}" class="form-control" id="nama" placeholder="Username">
              </div>
              <div class="form-group">
                <label for="nama">Kontak</label>
                <input type="text" name="employee_contact" value="{{ old('employee_contact', $data->employee_contact) }}" class="form-control" id="employee_contact" placeholder="Nomor Kontak" >
              </div>
              <div class="form-group">
                <label for="alamat">Alamat</label>
                <textarea class="form-control" rows="2" placeholder="Alamat" name="employee_address">{{ $data->employee_address }}</textarea>
              </div>
              <div class="form-group">
                <label for="kontak">Tipe Karyawan</label>
                <select class="form-control" id="tipe" name="employee_type">
                  <option value="Laundry" {{ $data->employee_type == 'Laundry' ? ' selected' : '' }} >Laundry</option>
                  <option value="Steam" {{ $data->employee_type == 'Steam' ? ' selected' : '' }} >Steam</option>
                </select>
              </div>
              <div class="form-group" style="display : {{ $data->employee_type === 'Steam' ? 'none' : '' }}" id="gaji">
                <label for="employee_sallary" id="empsal">Gaji</label>
                <input type="text" name="employee_sallary" class="form-control" value="{{ old('employee_sallary', $data->employee_sallary) }}" id="employee_sallary" placeholder="Gaji Karyawan" >
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
              <input type="text" class="form-control" value="{{ $data->employee_created_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Dibuat Tgl</label>
              <input type="text" class="form-control" value="{{ \carbon\carbon::parse($data->employee_created_at)->format('d-M-Y')}}" readonly>
            </div>
            @if (!empty($data->employee_modified_at))
            <div class="col-12">
              <label>Diubah Oleh</label>
              
              <input type="text" class="form-control" value="{{ $data->employee_modified_by}}" readonly>
            </div>
            <div class="col-12">
              <label>Diubah Tgl</label>
              <input type="text" class="form-control"value="{{ \carbon\carbon::parse($data->employee_modified_at)->format('d-M-Y')}}" readonly>
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

      $('#tipe').on('change', function(){
        let tipe = $('#tipe').val();
        tipe === 'Laundry' ? $('#empsal').text('Gaji(Bulan) Khusus Laundry') : $('#empsal').text('') ;
        tipe === 'Steam' ? $('#gaji').hide() : $('#gaji').show() ;
      });

      $('#delete').click(function(){
        modalPopup('Hapus Data'
          , '{{action("EmployeeController@postDelete")}}'
          , $('#csid').val()
          , 'Hapus'
          , 'Delete'
          , $('#employee_name').val())
      });
    });
</script>
@endsection