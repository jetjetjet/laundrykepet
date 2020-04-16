<!DOCTYPE html>
<html>
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
  <title>Cetak</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="{{ url('/') }}/dist/css/adminlte.min.css">
  <style>
    p{
      margin-bottom:0px;
    }
    .total td{
      padding: 0px;
    }
  </style>
</head>
<body>
  <div class="wrapper">
    @yield('section-print')
  </div>
</body>
</html>