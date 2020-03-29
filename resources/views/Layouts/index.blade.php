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
    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        @include('Layouts.notification')
      </li>
    </ul>
  </nav>

  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="index3.html" class="brand-link">
      <img src="{{ url('/') }}/dist/img/AdminLTELogo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Aplikasi</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="{{ url('/') }}/dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block">Alexander Pierce</a>
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
<script src="{{ url('/') }}/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js"></script>
<script src="{{ url('/') }}/dist/js/adminlte.js"></script>
<script src="{{ url('/') }}/dist/js/app.js"></script>
<script src="{{ url('/') }}/dist/js/typeahead.bundle.js"></script>
<script src="{{ url('/') }}/plugins/jquery-mousewheel/jquery.mousewheel.js"></script>
<script src="{{ url('/') }}/plugins/raphael/raphael.min.js"></script>

<script src="{{ url('/') }}/plugins/toastr/toastr.min.js"></script>
@yield('index-js')
</body>
</html>
