
@section('section-print')
<style>
.table td, .table th {
    padding: 5px !important;
}
</style>
  <div class="row">
    <div class="col-12">
      <h2 class="page-header">
        <i class="fas fa-globe"></i> Laundry
        <small class="float-right">Tgl: 2/10/2014</small>
      </h2>
    </div>
  </div>
  <div class="row invoice-info">
      <div class="col-sm-6 invoice-col">
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">No. Invoice</th>
              <td>: {{ $laundry_invoice }}</td>
            </tr>
            <tr>
              <th>Nama Pelanggan</th>
              <td>: {{ $laundry_customer_name }}</td>
            </tr>
            <tr>
              <th>Alamat</th>
              <td>: {{ $laundry_customer_address }}</td>
            </tr>
            <tr>
              <th>Kontak</th>
              <td>: {{ $laundry_customer_phone }}</td>
            </tr>
            <tr>
              <th>Tgl. Selesai</th>
              <td>: {{ $laundry_est_date }}</td>
            </tr>
            <tr>
              <th>Delivery</th>
              <td>: {{ $laundry_delivery }}</td>
            </tr>
          </table>
        </div>
      </div>
      <div class="col-sm-6 invoice-col">
        <b></b>
        <br>
        <br>
        <b>Order ID:</b> 4F3S8J<br>
        <b>Payment Due:</b> 2/22/2014<br>
        <b>Account:</b> 968-34567
      </div>
    </div>
    <div class="row">
      <div class="col-12 table-responsive">
        <table class="table table-striped">
          <thead>
          <tr>
            <th>Qty</th>
            <th>Product</th>
            <th>Serial #</th>
            <th>Description</th>
            <th>Subtotal</th>
          </tr>
          </thead>
          <tbody>
          <tr>
            <td>1</td>
            <td>Call of Duty</td>
            <td>455-981-221</td>
            <td>El snort testosterone trophy driving gloves handsome</td>
            <td>$64.50</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Need for Speed IV</td>
            <td>247-925-726</td>
            <td>Wes Anderson umami biodiesel</td>
            <td>$50.00</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Monsters DVD</td>
            <td>735-845-642</td>
            <td>Terry Richardson helvetica tousled street art master</td>
            <td>$10.70</td>
          </tr>
          <tr>
            <td>1</td>
            <td>Grown Ups Blue Ray</td>
            <td>422-568-642</td>
            <td>Tousled lomo letterpress</td>
            <td>$25.99</td>
          </tr>
          </tbody>
        </table>
      </div>
      <div class="row">
      <div class="col-6">
      </div>
      <div class="col-6">
        <p class="lead">Amount Due 2/22/2014</p>
        <div class="table-responsive">
          <table class="table">
            <tr>
              <th style="width:50%">Subtotal:</th>
              <td>$250.30</td>
            </tr>
            <tr>
              <th>Tax (9.3%)</th>
              <td>$10.34</td>
            </tr>
            <tr>
              <th>Shipping:</th>
              <td>$5.80</td>
            </tr>
            <tr>
              <th>Total:</th>
              <td>$265.24</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
    </div>
@endsection