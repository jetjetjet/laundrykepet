<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <meta http-equiv="x-ua-compatible" content="ie=edge">

  <title>Aplikasi Laundry - {{ $title }}</title>

  <link rel="stylesheet" href="{{ url('/') }}/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
  @yield('index-css')
  
  <link rel="stylesheet" href="{{ url('/') }}/plugins/toastr/toastr.min.css">
  <link rel="stylesheet" href="{{ url('/') }}/dist/css/adminlte.min.css">
  
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        @include('Layouts.notification')
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="{{ url('/') }}" class="brand-link">
    @if(Setting::getLogo())
    <img src="{{ asset('storage/images/'.Setting::getLogo()) }}"
      alt="AdminLTE Logo"
      class="brand-image img-circle elevation-3"
      style="opacity: .8">
    @endif
      <span class="brand-text font-weight-light">{{Setting::getAppName()->setting_value}}</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">{{ session('full_name') }}</a>
        </div>
      </div>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        @include('Layouts.sidebar')
      </nav>
    </div>
  </aside>

  <div class="content-wrapper">
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">{{ $title }}</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
              @yield('bread-master')
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <section class="content">
      @yield('main-body')
    </section>
  </div>

  <div class="modal fade" id="myModal" role="dialog">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h6 class="modal-title" id="modalTitle"></h6>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-d-none="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-danger btn-sm d-none modal-action-cancel" data-dismiss="modal">Batal</button>
          <button type="button" style="min-width: 75px;" class="btn btn-danger btn-sm d-none modal-action-delete font-bold"><span class="fa fa-trash fa-fw"></span>Hapus</button>
          <button type="button" style="min-width: 75px;" class="btn btn-default btn-sm d-none modal-action-ok font-bold" data-dismiss="modal">Ok</button>
          <button type="button" style="min-width: 75px;" class="btn btn-success btn-sm d-none modal-action-save font-bold">Simpan</button>
          <button type="button" style="min-width: 75px;" class="btn btn-sm d-none modal-action-yes-option2 font-bold" >Simpan</button>
          <button type="button" style="min-width: 75px;" class="btn btn-sm d-none modal-action-yes font-bold">Ya</button>
        </div>
      </div>
    </div>
  </div>

  <!-- Main Footer -->
  <footer class="main-footer">
    <strong>Copyright &copy; 2020 <a href="#">Aplikasi</a>.</strong>
    All rights reserved.
    <div class="float-right d-none d-sm-inline-block">
      <b>Version</b> 0.0.1
    </div>
  </footer>
</div>

<script src="{{ url('/') }}/plugins/jquery/jquery.min.js"></script>
<script src="{{ url('/') }}/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="{{ url('/') }}/plugins/jquery.validate.min.js"></script>
<script src="{{ url('/') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ url('/') }}/dist/js/adminlte.js"></script>
<script src="{{ url('/') }}/plugins/moment/moment.min.js"></script>
<script src="{{ url('/') }}/dist/js/typeahead.bundle.js"></script>
<script src="{{ url('/') }}/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="{{ url('/') }}/plugins/raphael/raphael.min.js"></script>

<script src="{{ url('/') }}/plugins/toastr/toastr.min.js"></script>
@yield('index-js')
<script src="{{ url('/') }}/dist/js/app.js"></script>
</body>
</html>
