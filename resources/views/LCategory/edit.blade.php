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
                <label>{{trans('fields.type') ." ". trans('fields.category') }}</label>
                <div class="input-group input-group-sm">
                  <select class="form-control" id="tipe" name="lcategory_lctype_id">
                    @if($data->lcategory_lctype_id)
                      <option value="{{$data->lcategory_lctype_id}}" selected="selected">{{$data->lcategory_lctype_name}}</option>
                    @endif
                  </select>
                  @if(Perm::can(['lctype_tambah']))
                  <div class="input-group-append">
                    <button type="button" id="tmbhType" class="btn btn-info btn-flat">Tambah Baru</button>
                  </div>
                  @endif
                </div>
              </div>
              <div class="form-group">
                <label for="nama">Hari Pengerjaan</label>
                <input type="number" name="lcategory_days" value="{{  old('lcategory_days', $data->lcategory_days) }}" class="form-control" id="lcategory_days" placeholder="Jumlah Hari Pengerjaan">
              </div>
              <div class="form-group">
                <label for="harga">Harga</label>
                <input type="number" name="lcategory_price" value="{{ $data->lcategory_price }}" class="form-control" id="lcategory_price" placeholder="Harga">
              </div>
              @if(Perm::can(['laundryKategori_simpan']))
                <button type="submit" class="btn btn-sm btn-primary"><i class="fa fa-save fa-fw"></i>&nbsp;Simpan</button>
              @endif
              @if($data->id)
                @if(Perm::can(['laundryKategori_simpan']))
                <a href="{{action('LCategoryController@getEdit')}}" class="btn btn-sm btn-success" >
                  <i class="fa fa-plus fa-fw"></i>&nbsp;Tambah Baru
                </a>
                @endif
                @if(Perm::can(['laundryKategori_hapus']))
                <a href="#" class="btn btn-sm btn-danger float-right" 
                    delete-title="Konfirmasi Hapus Data"
                  delete-action="{{ action('LCategoryController@postDelete', array('id' => $data->id)) }}"
                  delete-message="Apakah anda yakin untuk menghapus data ini?"
                  delete-success-url="{{ action('LCategoryController@index') }}">
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

  <div id="typePopup" style="display:none;">
    <div class="form-horizontal">
      <div class="form-group required">
        <label for="nama">{{trans('fields.name') ." ". trans('fields.category')}}</label>
        <input type="text"  name="lctype_name" placeholder="{{trans('fields.name') .' '. trans('fields.category')}}" class="form-control" required>
      </div>
    </div>
  </div>
@endsection


@section('form-js')
<script>
    $(document).ready(function (){
      $('[type=number]').setupMask(0);

      //Modal Tipe
      $('#tmbhType').on('click', function() {
        var modal = showPopupForm(
        $(this),
        { btnType: 'primary', keepOpen: true },
        "{{ trans('fields.addNew') .' '. trans('fields.category') }}",
        $('#typePopup'),
        '{{ action("LctypeController@postEdit") }}',
        function ($form){
            return {
              lctype_name: $form.find('[name=lctype_name]').val(),
              lctype_unit: $form.find('[name=lctype_unit]').val(),
              modal: $form.find('[name=modal]').val()
            };
        },
        //callback
        function (data){
          console.log(1, data.messages);
            toastr.success(data.messages)
        });
      });

      //cari tipe
      inputSearch('#tipe', '{{ action("LctypeController@searchType") }}', '85%', function(item) {
        return {
          text: item.lctype_name,
          id: item.id,
        }
      });
    })
</script>
@endsection