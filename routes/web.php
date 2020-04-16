<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });

// Auth::routes();

Route::get('login', 'LoginController@getLogin');
Route::get('logout', 'LoginController@getLogoff');
Route::post('login', 'LoginController@postLogin');

Route::group(array('middleware' => 'auth'), function ()
{
    Route::get('/', 'HomeController@index');
    Route::get('/Dashboar/Data', 'HomeController@getDataDash');    

    Route::get('Absen/', 'LAbsenController@index')->middleware('can:labsen_list');
    Route::get('Absen/List', 'LAbsenController@getList')->middleware('can:labsen_list');
    Route::get('Absen/Edit/{id?}', 'LAbsenController@getEdit')->middleware('can:labsen_lihat');
    Route::post('Absen/Save/{id?}', 'LAbsenController@postEdit')->middleware('can:labsen_simpan');
    Route::post('Absen/Delete/{id?}', 'LAbsenController@postDelete')->middleware('can:labsen_hapus');
    
    Route::get('Category/', 'CategoryController@index')->middleware('can:laundryKategori_list');
    Route::get('Category/Lists', 'CategoryController@getCategoryLists')->middleware('can:laundryKategori_list');
    Route::get('Category/Edit/{id?}', 'CategoryController@getEdit')->middleware('can:laundryKategori_lihat');
    Route::post('Category/Save/{id?}', 'CategoryController@postEdit')->middleware('can:laundryKategori_simpan');
    Route::post('Category/Delete/{id?}', 'CategoryController@postDelete')->middleware('can:laundryKategori_hapus');

    Route::get('Customers/', 'CustomersController@index')->middleware('can:pelanggan_list');
    Route::get('Customers/Lists', 'CustomersController@getCustomerLists')->middleware('can:pelanggan_list');
    Route::get('Customers/Edit/{id?}', 'CustomersController@getEdit')->middleware('can:pelanggan_lihat');
    Route::get('Customers/SearchCust', 'CustomersController@searchCustomer')->middleware('can:pelanggan_cari');
    Route::post('Customers/Save/{id?}', 'CustomersController@postEdit')->middleware('can:pelanggan_simpan');
    Route::post('Customers/Delete/{id?}', 'CustomersController@postDelete')->middleware('can:pelanggan_hapus');

    Route::get('DataLaundry/', 'DataLaundryController@index')->middleware('can:laundry_list');
    Route::get('DataLaundry/Lists', 'DataLaundryController@getLists')->middleware('can:laundry_list');

    Route::get('Employee/', 'EmployeeController@index')->middleware('can:karyawan_list');
    Route::get('Employee/Lists', 'EmployeeController@getEmployeeLists')->middleware('can:karyawan_list');
    Route::get('Employee/Edit/{id?}', 'EmployeeController@getEdit')->middleware('can:karyawan_lihat');
    Route::get('Employee/Search', 'EmployeeController@searchEmployee')->middleware('can:karyawan_cari');
    Route::post('Employee/Save/{id?}', 'EmployeeController@postEdit')->middleware('can:karyawan_simpan');
    Route::post('Employee/Delete/{id?}', 'EmployeeController@postDelete')->middleware('can:karyawan_hapus');

    Route::get('Lexpenses/','LexpensesController@index')->middleware('can:karyawan_hapus');
    Route::get('Lexpenses/List','LexpensesController@getGrid')->middleware('can:karyawan_hapus');
    Route::get('Lexpenses/Edit/{id?}', 'LexpensesController@getEdit')->middleware('can:karyawan_hapus');
    Route::post('Lexpenses/Save/{id?}', 'LexpensesController@postEdit')->middleware('can:karyawan_hapus');
    Route::post('Lexpenses/Delete/{id?}', 'LexpensesController@postDelete')->middleware('can:karyawan_hapus');

    Route::get('Laundry/Input/{id?}', 'LaundryController@input')->middleware('can:laundry_lihat');
    route::get('Laundry/Print/{id?}', 'LaundryController@generateReceipt')->middleware('can:laundry_cetak');
    Route::post('Laundry/Save/{id?}', 'LaundryController@postEdit')->middleware('can:laundry_simpan');
    Route::post('Laundry/ChangeStatus/{id?}/{mode?}', 'LaundryController@postUbahStatus')->middleware('can:laundry_ubahStatus');
    Route::post('Laundry/Pickup/{id?}', 'LaundryController@postPickup')->middleware('can:laundry_antar');
    Route::post('Laundry/Delivery/{id?}', 'LaundryController@postDelivery')->middleware('can:laundry_antar');
    Route::post('Laundry/Delete/{id?}', 'LaundryController@postDelete')->middleware('can:laundry_hapus');

    Route::get('LCategory/', 'LCategoryController@index')->middleware('can:laundryKategori_list');
    Route::get('LCategory/DropdownList', 'LCategoryController@getDropDownList');
    Route::get('LCategory/Lists', 'LCategoryController@getGrid')->middleware('can:laundryKategori_list');
    Route::get('LCategory/Edit/{id?}', 'LCategoryController@getEdit')->middleware('can:laundryKategori_lihat');
    Route::get('LCategory/SearchCategory', 'LCategoryController@getDropDownList')->middleware('can:laundryKategori_cari');
    Route::post('LCategory/Save/{id?}', 'LCategoryController@postEdit')->middleware('can:laundryKategori_simpan');
    Route::post('LCategory/Delete/{id?}', 'LCategoryController@postDelete')->middleware('can:laundryKategori_hapus');

    Route::get('Role/','RoleController@index')->middleware('can:peran_list');
    Route::get('Role/Lists', 'RoleController@getRoleLists')->middleware('can:peran_list');
    Route::get('Role/Edit/{id?}', 'RoleController@getEdit')->middleware('can:peran_lihat');
    Route::post('Role/Save', 'RoleController@postEdit')->middleware('can:peran_simpan');
    Route::post('Role/Delete/{id?}', 'RoleController@postDelete')->middleware('can:peran_hapus');

    Route::get('SCategory/','SCategoryController@index')->middleware('can:steamKategori_list');
    Route::get('SCategory/List','SCategoryController@getGrid')->middleware('can:steamKategori_list');
    Route::get('SCategory/Edit/{id?}', 'SCategoryController@getEdit')->middleware('can:steamKategori_lihat');
    Route::post('Scategory/Save/{id?}', 'SCategoryController@postEdit')->middleware('can:steamKategori_simpan');
    Route::post('Scategory/Delete/{id?}', 'SCategoryController@postDelete')->middleware('can:steamKategori_hapus');

    Route::get('Users/', 'UserController@index')->middleware('can:user_list');
    Route::get('Users/Lists', 'UserController@getUserLists')->middleware('can:user_list');
    Route::get('Users/Edit/{id?}', 'UserController@getEdit')->middleware('can:user_lihat');
    Route::post('Users/Save/{id?}', 'UserController@postEdit')->middleware('can:user_simpan');
    Route::post('Users/Delete/{id?}', 'UserController@postDelete')->middleware('can:user_hapus');
    Route::post('Users/ChangePassword/{id?}', 'UserController@postChangePassword')->middleware('can:user_simpan');

    Route::get('Laporan/Laundry', 'ReportController@getLaundryReport')->middleware('can:laporan_lihat');
    //Route::get('Laporan/Laundry/List', 'ReportController@getLaundryReport');

    Route::get('Setting/','SettingController@index')->middleware('can:setting_list');
    Route::get('Setting/List','SettingController@getGrid')->middleware('can:setting_list');
    Route::get('Setting/Edit/{id?}', 'SettingController@getEdit')->middleware('can:setting_lihat');
    Route::post('Setting/Save/{id?}', 'SettingController@postEdit')->middleware('can:setting_simpan');
    Route::post('Setting/Delete/{id?}', 'SettingController@postDelete')->middleware('can:setting_hapus');

    Route::get('Expense/','ExpenseController@index')->middleware('can:pengeluaran_list');
    Route::get('Expense/List','ExpenseController@getGrid')->middleware('can:pengeluaran_list');
    Route::get('Expense/Edit/{id?}', 'ExpenseController@getEdit')->middleware('can:pengeluaran_lihat');
    Route::post('Expense/Save/{id?}', 'ExpenseController@postEdit')->middleware('can:pengeluaran_simpan');
    Route::post('Expense/Delete/{id?}', 'ExpenseController@postDelete')->middleware('can:pengeluaran_hapus');

});
