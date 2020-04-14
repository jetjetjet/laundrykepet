@extends('Layouts.print')
@section('section-print')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12" style="text-align:center">
        <h2 class="page-header">Laundry</h2>
        <p>Jl. Sisinga Mangaraja No.22 Kota Sungai Penuh</p>
        <p>Telp. 0748-323810</p>
        <hr>
        <p>Invoice Laundry</p>
        <p>{{$data->laundry_created_at}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <table class="table table-borderless total">
          <tr>
            <td>No. Invoice</td>
            <td>: {{$data->laundry_invoice}}</td>
          </tr>
          <tr>
            <td width="25%">Pelanggan</td>
            <td>: {{$data->customer_name}}</td>
          </tr>
          <tr>
            <td>Telp/Hp</td>
            <td>: {{$data->customer_phone}}</td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>: {{$data->customer_address}}</td>
          </tr>
          <tr>
            <td>Delivery</td>
            <td>: {{ $data->laundry_delivery == true ? 'Ya' : 'Tidak'}}</td>
          </tr>
        </table>
      </div>
      <div class="col-6">
      </div>
    </div>
    <div class="row">
      <table class="table">
        <thead>
          <tr>
            <th scope="col">Item</th>
            <th scope="col">Tgl. Selesai</th>
            <th scope="col">Harga</th>
            <th scope="col">Qty</th>
            <th scope="col">Total</th>
          </tr>
        </thead>
        <tbody>
          @foreach($data->sub as $sub)
            <tr>
              <td>{{ $sub->ldetail_lcategory_name}}</td>
              <td>{{ $sub->ldetail_end_date}}</td>
              <td>{{ $sub->price}}</td>
              <td>{{ $sub->ldetail_qty}}</td>
              <td>{{ $sub->ldetail_total}}</td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
    <hr />
    <div class="row">
      <div class="col-9">
      </div>
      <div class="col-3">
        <table class="table table-borderless total">
          <tr>
            <td>Total</td>
            <td>: {{$data->total}}</td>
          </tr>
          <tr>
            <td>Bayar</td>
            <td>: {{$data->laundry_paid}}</td>
          </tr>
          <tr>
            @if($data->diff != 0)
              <td>Kurang Bayar</td>
              <td>: {{ $data->diff }}</td>
            @else
              <td><strong>LUNAS</strong></td>
            @endif
          </tr>
        </table>
      </div>
    </div>
    <div class="row">
      <div class="col-12" style="text-align:center">
        <strong>Terima Kasih Atas Kepercayaan Anda</strong>
      </div>
    </div>
  </div>
</section>
@endsection