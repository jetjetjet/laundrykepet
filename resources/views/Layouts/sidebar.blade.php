<?php
  $master = (Perm::can(['karyawan_list']) || Perm::can(['user_list'])
    || Perm::can(['peran_list']) || Perm::can(['pelanggan_list']) || Perm::can(['kategoriLaundry_daftar']));
  $masterUrl = (request()->is('Employee*') || request()->is('Users*') ||request()->is('Role*')
    || request()->is('Customers*') || request()->is('LCategory*'));
  $laundry = (Perm::can(['laundry_simpan'])|| Perm::can(['laundry_list']));
  $laundryUrl = (request()->is('DataLaundry*') || request()->is('Laundry*'));
?>

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  <li class="nav-item">
    <a href="{{ url('/') }}" class="nav-link {{ (request()->is('/')) ? 'active' : '' }}">
      <i class="nav-icon fa fa-home"></i>
      <p>Beranda</p>
    </a>
  </li>
  @if ($master)
  <li class="nav-item has-treeview menu-{{$masterUrl ? 'open' : 'close' }}">
    <a href="#" class="nav-link {{ $masterUrl ? 'active' : '' }}">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>Master<i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">
    @if (Perm::can(['karyawan_list']))
      <li class="nav-item">
        <a href="{{ action("EmployeeController@index") }}" class="nav-link {{ (request()->is('Employee*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Karyawan</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['user_list']))
      <li class="nav-item">
        <a href="{{ action("UserController@index") }}" class="nav-link {{ (request()->is('Users*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>User</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['peran_list']))
      <li class="nav-item">
        <a href="{{ action("RoleController@index") }}" class="nav-link {{ (request()->is('Role*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Hak Akses</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['pelanggan_list']))
      <li class="nav-item">
        <a href="{{ action("CustomersController@index") }}" class="nav-link {{ (request()->is('Customers*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Pelanggan</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['laundryKategori_list']))
      <li class="nav-item">
        <a href="{{ action("LCategoryController@index") }}" class="nav-link {{ (request()->is('LCategory*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Kategori Laundry</p>
        </a>
      </li>
    @endif
  @if (Perm::can(['kategoristeam']))
      <li class="nav-item">
        <a href="{{ action("SCategoryController@index") }}" class="nav-link {{ (request()->is('SCategory*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Kategori Steam</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['setting']))
      <li class="nav-item">
        <a href="{{ action("SettingController@index") }}" class="nav-link {{ (request()->is('setting*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Setting</p>
        </a>
      </li>
    @endif
    </ul>
  </li>
  @endif
<<<<<<< HEAD
    
  <li class="nav-item has-treeview menu-open">
    <a href="#" class="nav-link ">
      <i class="nav-icon fas fa-tshirt"></i>
      <p>Laundry<i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">
      <li class="nav-item">
        <a href="{{ action("LaundryController@input") }}" class="nav-link {{ (request()->is('Laundry/Input*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Input Laundry</p>
        </a>
      </li>
      <li class="nav-item">
        <a href="{{ action("DataLaundryController@index") }}" class="nav-link {{ (request()->is('DataLaundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Data Laundry</p>
        </a>
      </li>
    </ul>
  </li>
=======
  @if($laundry)
    <li class="nav-item has-treeview menu-open">
      <a href="#" class="nav-link {{ $laundryUrl ? 'active' : '' }}">
        <i class="nav-icon fas fa-tshirt"></i>
        <p>Laundry<i class="right fas fa-angle-left"></i></p>
      </a>
      <ul class="nav nav-treeview">
        @if(Perm::can(['laundry_simpan']))
        <li class="nav-item">
          <a href="{{ action("LaundryController@input") }}" class="nav-link {{ (request()->is('Laundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Input Laundry</p>
          </a>
        </li>
        @endif
        @if(Perm::can(['laundry_list']))
        <li class="nav-item">
          <a href="{{ action("DataLaundryController@index") }}" class="nav-link {{ (request()->is('DataLaundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Data Laundry</p>
          </a>
        </li>
        @endif
      </ul>
    </li>
  @endif
  @if(Perm::can(['laporan_lihat']))
    <li class="nav-item has-treeview menu-{{(request()->is('Laporan*')) ? 'open' : 'close' }}">
      <a href="#" class="nav-link {{ (request()->is('Laporan*')) ? 'active' : '' }}">
        <i class="nav-icon fa fa-file-alt"></i>
        <p>Laporan<i class="right fas fa-report"></i></p>
      </a>
      <ul class="nav nav-treeview">
        @if(Perm::can(['laporan_lihat']))
        <li class="nav-item">
          <a href="{{ action("ReportController@indexLaundry") }}" class="nav-link {{ (request()->is('Laundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Laporan Laundry</p>
          </a>
        </li>
        @endif
        @if(Perm::can(['laporan_lihat']))
        <li class="nav-item">
          <a href="{{ action("DataLaundryController@index") }}" class="nav-link {{ (request()->is('DataLaundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Data Laundry</p>
          </a>
        </li>
        @endif
      </ul>
    </li>
  @endif
>>>>>>> 8610241b0d410c61b7721888b0c194e6d0d47636
  <li class="nav-item">
    <a href="{{ action("ExpenseController@index") }}" class="nav-link">
      <i class="nav-icon fas fa-th"></i>
      <p>Expense</p>
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ action("LoginController@getLogoff") }}" class="nav-link">
      <i class="nav-icon fas fa-th"></i>
      <p>Keluar</p>
    </a>
  </li>
  <!-- <li class="nav-header">EXAMPLES</li> -->
</ul>
