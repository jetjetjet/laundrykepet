<?php
  $master = (Perm::can(['karyawan_daftar']) || Perm::can(['user_daftar'])
    || Perm::can(['peran_daftar']) || Perm::can(['pelanggan_daftar']) || Perm::can(['kategoriLaundry_daftar']));
  $masterUrl = (request()->is('Employee*') || request()->is('Users*') ||request()->is('Role*')
    || request()->is('Customers*') || request()->is('LCategory*'));  
?>

<ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
  @if ($master)
  <li class="nav-item has-treeview menu-{{$masterUrl ? 'open' : 'close' }}">
    <a href="#" class="nav-link {{ $masterUrl ? 'active' : '' }}">
      <i class="nav-icon fas fa-tachometer-alt"></i>
      <p>Master<i class="right fas fa-angle-left"></i></p>
    </a>
    <ul class="nav nav-treeview">
    @if (Perm::can(['karyawan_daftar']))
      <li class="nav-item">
        <a href="{{ action("EmployeeController@index") }}" class="nav-link {{ (request()->is('Employee*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Karyawan</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['user_daftar']))
      <li class="nav-item">
        <a href="{{ action("UserController@index") }}" class="nav-link {{ (request()->is('Users*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>User</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['peran_daftar']))
      <li class="nav-item">
        <a href="{{ action("RoleController@index") }}" class="nav-link {{ (request()->is('Role*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Hak Akses</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['pelanggan_daftar']))
      <li class="nav-item">
        <a href="{{ action("CustomersController@index") }}" class="nav-link {{ (request()->is('Customers*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Pelanggan</p>
        </a>
      </li>
    @endif
    @if (Perm::can(['kategoriLaundry_daftar']))
      <li class="nav-item">
        <a href="{{ action("LCategoryController@index") }}" class="nav-link {{ (request()->is('LCategory*')) ? 'active' : '' }}"><i class="far fa-circle nav-icon"></i>
          <p>Kategori Laundry</p>
        </a>
      </li>
    @endif
    </ul>
  </li>
  @endif

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
    </ul>
  </li>
  <li class="nav-item">
    <a href="{{ action("LoginController@getLogoff") }}" class="nav-link">
      <i class="nav-icon fas fa-th"></i>
      <p>Keluar</p>
    </a>
  </li>
  <!-- <li class="nav-header">EXAMPLES</li> -->
</ul>
