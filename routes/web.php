<?php

use App\Filament\Resources\NoResource\Pages\CustomTransaction;
use App\Filament\Resources\TransactionResource\Pages\ViewTransaction;
use App\Http\Controllers\FrontviewController;
use App\Http\Controllers\ReatlimeDataLoader;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\WhatsAppController;
use App\Http\Controllers\WhatsappHandler;
use App\Models\transaction;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

use Illuminate\Support\Facades\Cache;



use App\Http\Controllers\TransactionController;

Route::post('/send-faktur/{id}', [ReatlimeDataLoader::class, 'generateAndSendPdf']);




Route::post('/whatsapp/logout', [WhatsAppController::class, 'logout'])->name('whatsapp.logout');
// routes/api.php
// Route::get('/detailTransaksi/{id}', [ReatlimeDataLoader::class, 'show']);
Route::put('/updateTransaction/{transactionId}', [ReatlimeDataLoader::class, 'updateSnapToken']);
// routes/api.php
Route::post('/proses-selesai/{id}', [ReatlimeDataLoader::class, 'prosesSelesai']);
Route::post('/proses-bayar/{id}', [ReatlimeDataLoader::class, 'prosesBayar']);
Route::get('/transaction/view', action: ViewTransaction::class)->name('transaction.view');




Route::get('/tabel-preview-transaction', [ReatlimeDataLoader::class, 'tabelPreviewTransaction']);
Route::get('/service-form-transaction', [ReatlimeDataLoader::class, 'serviceFormTransaction']);
Route::post('/save-temp-data', [ReatlimeDataLoader::class, 'saveTempData']);
Route::post('/save-unique-id', [ReatlimeDataLoader::class, 'saveIdToJson']);
Route::post('/save-total-harga', [ReatlimeDataLoader::class, 'saveTotalHargaToJson']);
Route::get('/clear-temp-data', [ReatlimeDataLoader::class, 'clearTempData']);
// Route untuk menghapus data
Route::delete('/hapus-transaction/{itemId}', [ReatlimeDataLoader::class, 'hapusTransaction']);

// for customer transactions
Route::get('/generate-pdf/{id}', [ReatlimeDataLoader::class, 'generatePdf']);
Route::get('/api/detailTransaksi/{id}', [ReatlimeDataLoader::class, 'getDetailTransaction']);
Route::get('/api/customers', [ReatlimeDataLoader::class, 'getCustomers']);
Route::post('/saveCustomerTransaction', [ReatlimeDataLoader::class, 'saveCustomerData']);
Route::post('/simpanTransaksi', [ReatlimeDataLoader::class, 'simpanTransaksi'])->name('simpanTransaksi');

Route::get('/get-all-data', [ReportController::class, 'getAllData']);

Route::get('/', function () {
    return view('welcome');
});

Route::get('/', [FrontviewController::class, 'index'])->name('front.index');
Route::get('/detail-services/{id}', [FrontviewController::class, 'detailServices'])->name('front.detailServices');


//autentikasi
Route::get('/login', [FrontviewController::class, 'login'])->name('login');
Route::get('/logout', [FrontviewController::class, 'logout'])->name('logout');
Route::get('/whatsapp-verification', [WhatsappHandler::class, 'whatsappVerification'])->name('whatsapp.verification');
Route::get('/resend-otp', [WhatsappHandler::class, 'resendOtp'])->name('whatsapp.resendOtp');
Route::post('/verify-otp', [WhatsappHandler::class, 'verifyOtp'])->name('whatsapp.verifyOtp');


Route::get('/profile', [FrontviewController::class, 'profile'])->name('profile');
Route::post('/add-to-cart', [FrontviewController::class, 'addToCart'])->name('addToCart');
Route::get('/realtime-data', [FrontviewController::class, 'realtimeData'])->name('realtimeData');
Route::get('/cart', [FrontviewController::class, 'viewCart'])->name('viewCart');
Route::post('/update-cart', [FrontviewController::class, 'updateCart'])->name('updateCart');
Route::post('/upload-file', [FrontviewController::class, 'handleUpload'])->name('uploadFile');
Route::delete('/cart/{id}', [FrontviewController::class, 'deleteCartItem'])->name('deleteCartItem');




Route::get('/check-session', function () {
    // Cek apakah 'verification_code' ada di session
    if (session()->has('verification_code')) {
        return response()->json([
            'status' => 'success',
            'verification_code' => session('verification_code'),
            'verification_code_expiration' => session('verification_code_expiration'),
            'number' => session('getWhatsappNumber'),
            'addCart' => session('addCart')
        ]);
    } else {
        return response()->json([
            'status' => 'error',
            'message' => 'No verification code found in session',
            'verification_code_expiration' => session('verification_code_expiration'),
            'number' => session('getWhatsappNumber'),
            'addCart' => session('addCart')
        ]);
    }
});