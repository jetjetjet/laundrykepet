
<?php $title = 'Dashboard'; ?>
@extends('Layouts.form-body')

@section('container')
  <section class="content">
    <div class="container-fluid">
      <div class="row">
        <div class="col-12">    
          <div class="panel">
            <div class="panel-heading">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group">
                    <label for="">Bulan</label>
                    <select id="bln" class="form-control filter">
                      @foreach($data->bln as $bln)
                        <option value="{{$bln->val}}" {{$bln->skrg ? 'Selected' : ''}}>{{$bln->bln}}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group">
                  <label for="">Tahun</label>
                    <select id="thn" class="form-control filter">
                      @foreach($data->thn as $t)
                        <option value="{{$t}}">{{$t}}</option>
                      @endforeach
                    </select>
                  </div>
                  </div>
                </div>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-12">
          <div class="info-box">
            <div class="chart">
              <canvas id="lineChart" style="position: relative; height:40vh; width:80vw"></canvas>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box mb-2 bg-success">
            <span class="info-box-icon"><i class="fa fa-shopping-cart"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Total Transaksi</span>
              <span class="info-box-number" id="total"></span>
            </div>
          </div>
          <div class="info-box mb-2 bg-success">
            <span class="info-box-icon"><i class="fa fa-calendar"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Belum Diproses</span>
              <span class="info-box-number" id="draft"></span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="fa fa-clock"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Dalam Proses</span>
              <span class="info-box-number" id="inprog"></span>
            </div>
          </div>
          <div class="info-box mb-3 bg-info">
            <span class="info-box-icon"><i class="far fa-check-circle"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Selesai</span>
              <span class="info-box-number" id="finish"></span>
            </div>
          </div>
        </div>
        <div class="col-12 col-sm-12 col-md-4">
          <div class="info-box mb-3 bg-danger">
            <span class="info-box-icon"><i class="fa fa-truck"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Diantar</span>
              <span class="info-box-number" id="deliv"></span>
            </div>
          </div>
          <div class="info-box mb-3 bg-danger">
            <span class="info-box-icon"><i class="fa fa-user"></i></span>
            <div class="info-box-content">
              <span class="info-box-text">Diambil</span>
              <span class="info-box-number" id="take"></span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@section('form-js')
<script>
  $(document).ready(function (){
    var a = getData();
    chart(a);

    $('.filter').on('change', function(){
      var data = {
        bulan: $('#bln').val(),
        tahun: $('#thn').val()
      };
      var get = getData(data);
      chart(get);
    })
  });
  
function getData(val)
{
  let data = $.ajax({
    url: '{{action("HomeController@getDataDash")}}',
    type: "GET",
    data: val,
    async: false, 
    success: function (data) {
        return data;
    }
  }).responseText;

  return JSON.parse(data);
}

function chart(data)
{
  var options = {
    type: 'line',
    data: {
      labels: data.chartTgl.split(','),
      datasets: [
        {
          label: 'Data Transaksi',
          data: data.chartTotal.split(','),
          borderWidth: 1
        },
      ]
    },
    options: {
      scales: {
          yAxes: [{
              stacked: true
          }]
      }
    }
  }

  new Chart($('#lineChart'), options);
  
  $('#total').text(data.total);
  $('#draft').text(data.draft);
  $('#inprog').text(data.executed);
  $('#finish').text(data.finished);
  $('#deliv').text(data.delivery);
  $('#take').text(data.taken);
}
</script>
@endsection