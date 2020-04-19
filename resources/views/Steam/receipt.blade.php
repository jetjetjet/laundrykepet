@extends('Layouts.print')
@section('section-print')

<section class="content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12" style="text-align:center">
        <h2 class="page-header">Steam</h2>
        <p>Jl. Sisinga Mangaraja No.22 Kota Sungai Penuh</p>
        <p>Telp. 0748-323810</p>
        <hr>
        <p>Invoice Steam</p>
        <p>{{$data->steam_created_at}}</p>
      </div>
    </div>
    <div class="row">
      <div class="col-6">
        <table class="table table-borderless total">
          <tr>
            <td>No. Invoice</td>
            <td>: {{$data->steam_invoice}}</td>
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
              <td>{{ $sub->sdetail_scategory_name}}</td>
              <td>{{ $sub->sdetail_end_date}}</td>
              <td>{{ $sub->price}}</td>
              <td>{{ $sub->sdetail_qty}}</td>
              <td>{{ $sub->sdetail_total}}</td>
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
            <td>: {{$data->steam_paid}}</td>
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