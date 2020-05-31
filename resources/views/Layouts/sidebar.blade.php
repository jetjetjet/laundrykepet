<?php
  $master = (Perm::can(['karyawan_list']) || Perm::can(['user_list'])
    || Perm::can(['peran_list']) || Perm::can(['pelanggan_list']) || Perm::can(['kategoriLaundry_daftar']));
  $masterUrl = (request()->is('Employee*') || request()->is('Users*') ||request()->is('Role*')
    || request()->is('Customers*') || request()->is('LCategory*'));
  $laundry = (Perm::can(['laundry_simpan'])|| Perm::can(['laundry_list']));
  $laundryUrl = (request()->is('DataLaundry*') || request()->is('Laundry*'));
  $steam = (Perm::can(['steam_simpan'])|| Perm::can(['steam_list']));
  $steamUrl = (request()->is('DataSteam*') || request()->is('Steam*'));

?>

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  <li class="nav-item">
    <a href="{{ url('/') }}" class="nav-link {{ (request()->is('/')) ? 'active' : '' }}">
      <i class="nav-icon fa fa-home"></i>
      <p>Beranda</p>
    </a>
  </li>
  @if(Perm::can(['labsen_lihat']))
    <li class="nav-item">
      <a href="{{ action("LAbsenController@index") }}" class="nav-link {{ (request()->is('Absen*')) ? 'active' : '' }}">
        <i class="nav-icon fa fa-users"></i>
        <p>{{ trans('fields.absen') }}</p>
      </a>
    </li>
  @endif
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

  @if($steam)
    <li class="nav-item has-treeview menu-open">
      <a href="#" class="nav-link {{ $steamUrl ? 'active' : '' }}">
        <i class="nav-icon fas fa-wrench"></i>
        <p>Steam<i class="right fas fa-angle-left"></i></p>
      </a>
      <ul class="nav nav-treeview">
        @if(Perm::can(['steam_simpan']))
        <li class="nav-item">
          <a href="{{ action("SteamController@input") }}" class="nav-link {{ (request()->is('Steam*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Input Steam</p>
          </a>
        </li>
        @endif
        @if(Perm::can(['steam_list']))
        <li class="nav-item">
          <a href="{{ action("DataSteamController@index") }}" class="nav-link {{ (request()->is('DataSteam*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Data Steam</p>
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
        <p>Laporan<i class="right fas fa-angle-left"></i></p>
      </a>
      <ul class="nav nav-treeview">
        @if(Perm::can(['laporan_lihat']))
        <li class="nav-item">
          <a href="{{ action("ReportController@getLaundryReport") }}" class="nav-link {{ (request()->is('Laporan/Laundry*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
            <p>Laporan Laundry</p>
          </a>
        </li>
        @endif
      </ul>
    </li>
  @endif
  @if(Perm::can(['pengeluaran_lihat']))
    <li class="nav-item">
      <a href="{{ action("ExpenseController@index") }}" class="nav-link {{ (request()->is('Expense*')) ? 'active' : '' }}">
        <i class="nav-icon fa fa-shopping-cart"></i>
        <p>{{ trans('fields.expense') }}</p>
      </a>
    </li>
  @endif
  @if(Perm::can(['pengeluaranLaundry_simpan']))
  <li class="nav-item">
    <a href="{{ action("LexpensesController@index") }}" class="nav-link">
      <i class="nav-icon fa fa-shopping-cart"></i>
      <p>Pengeluaran Laundry</p>
    </a>
  </li>
  @endif
  @if(Perm::can(['peminjam_simpan']))
  <li class="nav-item">
    <a href="{{ action("LoanController@index") }}" class="nav-link">
      <i class="nav-icon fa fa-clipboard"></i>
      <p>Pinjaman</p>
    </a>
  </li>
  @endif
  <!-- <li class="nav-header">EXAMPLES</li> -->
</ul>
