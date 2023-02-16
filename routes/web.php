<?php

use App\Http\Controllers\LaravelExcel\ImportController;
use Illuminate\Support\Facades\Route;

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
    return redirect()->route('index');
});

Route::get('/import', [ImportController::class, 'index'])->name('index');
Route::get('/seeBatch/{id}', [ImportController::class, 'seeBatch'])->name('seeBatch');
Route::post('/import', [ImportController::class, 'import'])->name('import');
Route::get('/export', [ImportController::class, 'export'])->name('export');
Route::get('/download', [ImportController::class, 'download'])->name('download');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
