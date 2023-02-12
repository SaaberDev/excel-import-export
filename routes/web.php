<?php

    use App\Models\User;
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
    //
});

Route::get('/import', [\App\Http\Controllers\ImportController::class, 'index'])->name('index');
Route::get('/seeBatch/{id}', [\App\Http\Controllers\ImportController::class, 'seeBatch'])->name('seeBatch');
Route::post('/import', [\App\Http\Controllers\ImportController::class, 'import'])->name('import');
Route::get('/export', [\App\Http\Controllers\ImportController::class, 'export'])->name('export');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';
