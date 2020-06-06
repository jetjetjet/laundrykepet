@extends('Layouts.form-body')
<?php $title = 'Data Pinjaman'; ?>
@section('breadNav')
  <li class="breadcrumb-item active"><a href="#">Pinjaman</a></li>
  <li class="breadcrumb-item active" aria-current="page">{{ empty($data->id) ? 'Tambah Data' : 'Ubah Data'}}</li>
@endsection
@section('container')
<div class="row">
  <div class="col-lg-8 col-xl-9">
    <div class="card">
      <div class="card-body pd-lg-25">
          <div class="col-lg-12 col-xl-12">
            <form action="{{ action("LoanController@postEdit") }}" method="POST" autocomplete="off">
              <input type="hidden" name="_token" value="{{ csrf_token() }}" />
              <input type="hidden" id="csid" name="id" value="{{ old('id', $data->id) }}" />
              <div class="form-group">
                  <label for="nama">Daftar Karyawan</label>
                  <div class="input-group input-group-sm">
                    @if(empty($data->laundry_executed_at))
                      <select class="form-control" id="empSearch" name="loan_employee_id">
                    @if($data->loan_employee_id)
                      <option value="{{$data->loan_employee_id}}" selected="selected">{{$data->loan_employee_name}}</option>
                    @endif
                      </select>
                    @endif
                  </div>
                </div>
              <div class="form-group">
                <label for="jumlah">Jumlah</label>
                <input type="number" name="loan_amount" value="{{ $data->loan_amount }}" class="form-control" id="jumlah" placeholder="Jumlah Pinjaman">
              </div>
              <div class="form-group">
                <label for="tenor">Jangka Waktu</label>
                <select class="form-control" id="tipe" name="loan_tenor">
                  @for($i=1;$i <= 12 ;$i++)
                    <option value="{{ $i }}" {{ $data->loan_tenor == $i ? ' selected' : '' }}>{{ $i }} Bulan</option>
                  @endfor
                </select>
              </div>
              @if($data->id)
              <div class="form-group">
                <label for="Lunas">Lunas</label>
                <input type="text" name="loan_amount" value="{{ $data->loan_paidoff == '1' ? 'Lunas' : 'Belum' }}" class="form-control" readonly>
              </div>
              @endif
              <div class="form-group form-sm">
                <label for="keterangan">Keterangan</label>
                <textarea class="form-control" rows="2" placeholder="Keterangan" name="loan_detail">{{ $data->loan_detail }}</textarea>
              </div>
              @if(Perm::can(['peminjam_simpan']) && $data->loan_paidoff != 1)
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
              @endif
              @if($data->id)
                @if(Perm::can(['peminjam_simpan']))
                  <a href="{{action('LoanController@getEdit')}}" class="btn btn-sm btn-success" >
                    <i class="fa fa-plus fa-fw"></i>&nbsp;Tambah Baru
                  </a>
                @endif
                @if(Perm::can(['peminjam_tambah']) && $data->loan_paidoff != 1)
                  <a href="#" class="btn btn-sm btn-danger float-right" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('LoanController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('LoanController@index') }}">
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
            <input type="text" class="form-control form-control-sm" value="{{ $data->loan_created_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Dibuat Tgl</label>
            <input type="text" class="form-control form-control-sm" value="{{ \carbon\carbon::parse($data->loan_created_at)->format('d-M-Y')}}" readonly>
          </div>
          @if (!empty($data->loan_modified_at))
          <div class="col-12">
            <label>Diubah Oleh</label>
            
            <input type="text" class="form-control form-control-sm" value="{{ $data->loan_modified_by}}" readonly>
          </div>
          <div class="col-12">
            <label>Diubah Tgl</label>
            <input type="text" class="form-control form-control-sm"value="{{ \carbon\carbon::parse($data->loan_modified_at)->format('d-M-Y')}}" readonly>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
  </div>
  @endif
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('[type=number]').setupMask(0);
      $('.select2').select2({
          placeholder: 'Pilih',
          searchInputPlaceholder: 'Search options'
        });

        $('#empSearch').on('change', function() {
      let selected = $(this).children("option:selected").val();
      if(selected != null){
        $('#simpan').prop('disabled', false);
        $('.add-row').prop('disabled', false);
      } else {
        $('.add-row').prop('disabled', true);
        $('#simpan').prop('disabled', true);
      }
    });

    //cari emp
    inputSearch('#empSearch', '{{ action("EmployeeController@searchEmployee") }}', 'resolve', function(item) {
      return {
        text: item.employee_name,
        id: item.id
      }
    });
    $('#empSearch').on('select2:select', function (e) {
      $('#empSearch').attr('data-has-changed', '1');
    });

    });
</script>
@endsection