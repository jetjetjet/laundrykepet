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
    
    Route::get('Category/', 'CategoryController@index')->middleware('can:kategori_daftar');
    Route::get('Category/Lists', 'CategoryController@getCategoryLists');
    Route::get('Category/Edit/{id?}', 'CategoryController@getEdit');
    Route::post('Category/Save/{id?}', 'CategoryController@postEdit');
    Route::post('Category/Delete/{id?}', 'CategoryController@postDelete');

    Route::get('Customers/', 'CustomersController@index');
    Route::get('Customers/Lists', 'CustomersController@getCustomerLists');
    Route::get('Customers/Edit/{id?}', 'CustomersController@getEdit');
    Route::get('Customers/SearchCust', 'CustomersController@searchCustomer');
    Route::post('Customers/Save/{id?}', 'CustomersController@postEdit');
    Route::post('Customers/Delete/{id?}', 'CustomersController@postDelete');

    Route::get('DataLaundry/', 'DataLaundryController@index');
    Route::get('DataLaundry/Lists', 'DataLaundryController@getLists');

    Route::get('Employee/', 'EmployeeController@index')->middleware('can:karyawan_daftar');
    Route::get('Employee/Lists', 'EmployeeController@getEmployeeLists')->middleware('can:karyawan_daftar');
    Route::get('Employee/Edit/{id?}', 'EmployeeController@getEdit')->middleware('can:karyawan_simpan');
    Route::get('Employee/Search', 'EmployeeController@searchEmployee');
    Route::post('Employee/Save/{id?}', 'EmployeeController@postEdit')->middleware('can:karyawan_simpan');
    Route::post('Employee/Delete/{id?}', 'EmployeeController@postDelete')->middleware('can:karyawan_hapus');

    Route::get('Laundry/Input/{id?}', 'LaundryController@input');
    route::get('Laundry/Print/{id?}', 'LaundryController@generateReceipt');
    Route::post('Laundry/Save/{id?}', 'LaundryController@postEdit');
    Route::post('Laundry/ChangeStatus/{id?}/{mode?}', 'LaundryController@postUbahStatus');
    Route::post('Laundry/Pickup/{id?}', 'LaundryController@postPickup');
    Route::post('Laundry/Delivery/{id?}', 'LaundryController@postDelivery');
    Route::post('Laundry/Delete/{id?}', 'LaundryController@postDelete');

    Route::get('LCategory/', 'LCategoryController@index')->middleware('can:kategoriLaundry_daftar');
    Route::get('LCategory/DropdownList', 'LCategoryController@getDropDownList');
    Route::get('LCategory/Lists', 'LCategoryController@getGrid')->middleware('can:kategoriLaundry_daftar');
    Route::get('LCategory/Edit/{id?}', 'LCategoryController@getEdit')->middleware('can:kategoriLaundry_simpan');
    Route::get('LCategory/SearchCategory', 'LCategoryController@getDropDownList')->middleware('can:kategoriLaundry_daftar');
    Route::post('LCategory/Save/{id?}', 'LCategoryController@postEdit')->middleware('can:kategoriLaundry_simpan');
    Route::post('LCategory/Delete/{id?}', 'LCategoryController@postDelete')->middleware('can:kategoriLaundry_hapus');

    Route::get('Role/','RoleController@index')->middleware('can:peran_daftar');
    Route::get('Role/Lists', 'RoleController@getRoleLists')->middleware('can:peran_daftar');
    Route::get('Role/Edit/{id?}', 'RoleController@getEdit')->middleware('can:peran_simpan');
    Route::post('Role/Save', 'RoleController@postEdit')->middleware('can:peran_simpan');
    Route::post('Role/Delete/{id?}', 'RoleController@postDelete')->middleware('can:peran_hapus');

    Route::get('SCategory/','SCategoryController@index');
    Route::get('SCategory/List','SCategoryController@getGrid');
    Route::get('SCategory/Edit/{id?}', 'SCategoryController@getEdit');
    Route::post('Scategory/Save/{id?}', 'SCategoryController@postEdit');
    Route::post('Scategory/Delete/{id?}', 'SCategoryController@postDelete');

    Route::get('Users/', 'UserController@index');
    Route::get('Users/Lists', 'UserController@getUserLists');
    Route::get('Users/Edit/{id?}', 'UserController@getEdit');
    Route::post('Users/Save/{id?}', 'UserController@postEdit');
    Route::post('Users/Delete/{id?}', 'UserController@postDelete');
    Route::post('Users/ChangePassword/{id?}', 'UserController@postChangePassword');

});
