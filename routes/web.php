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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::middleware(['auth'])->group(function () {
    
    Route::get('/get_barang', 'BarangController@getData')->name('getBarang');
    Route::get('/get_barang_transaksi', 'TransaksiController@getData')->name('getBarangForTransaksi');
    Route::resource('/warung', 'WarungController');

    Route::middleware(['has.warung'])->group(function () {
        Route::get('/home', 'HomeController@index')->name('home');
        Route::get('/transaksi', 'TransaksiController@index')->name('transaksi');
        Route::get('/transaksi/print/{id}', 'TransaksiController@print')->name('transaksi.print');
        Route::get('/transaksi/{id}', 'TransaksiController@show')->name('transaksi.show');
        Route::post('/transaksi', 'TransaksiController@store')->name('transaksi.store');
        Route::resource('/barang', 'BarangController');
        Route::get('/laporan', 'TransaksiController@laporan')->name('laporan');
    });
});
