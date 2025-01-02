<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientEmailController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\InvoiceItemController;
use App\Http\Controllers\InvoiceTemplateController;
//use App\Http\Controllers\PDFController;
//use App\Http\Controllers\PermissionController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\ImageUploadController;

Route::get('/', function () {
    return view('dashboard');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');


Route::middleware('auth')->group(function () {

    Route::get('/dashboard/stats', [DashboardController::class, 'getStats'])->name('dashboard.stats');



    Route::get('/', [ClientController::class])->middleware('Auth');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('user', UserController::class);
    Route::resource('role', RoleController::class);
    Route::resource('client', ClientController::class);
    Route::get('/client/create', [ClientController::class, 'create'])->name('client.create');
    Route::post('client/quickStore', [ClientController::class, 'quickStore'])->name('client.quickStore');
    Route::post('/client/{client}/update-primary-contact', [ClientController::class, 'updatePrimaryContact'])
        ->name('client.update-primary-contact');

    /*ClientEmails*/
    Route::resource('client.emails', ClientEmailController::class)->only([
        'index', 'store', 'show'
    ]);

    Route::post('client/search', [ClientController::class, 'search'])->name('client.search');

    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings', [SettingController::class, 'store'])->name('settings.store');
    Route::put('/settings/update', [SettingController::class, 'update'])->name('settings.update');
    Route::delete('/settings/{setting}', [SettingController::class, 'destroy'])->name('settings.destroy');
    Route::post('/settings/bulk-update', [SettingController::class, 'bulkUpdate'])->name('settings.bulk-update');


    //Route::resource('invoices', InvoiceController::class);
    Route::get('/invoices', [InvoiceController::class, 'index'])->name('invoice.index');
    Route::get('/invoice/{invoice}/show', [InvoiceController::class, 'show'])->name('invoice.show');
    Route::get('/invoice/create', [InvoiceController::class, 'create'])->name('invoice.create');
    Route::post('/invoice', [InvoiceController::class, 'store'])->name('invoice.store');  // Changed from invoices.store to invoice.store
    Route::get('/invoice/{invoice}/edit', [InvoiceController::class, 'edit'])->name('invoice.edit');
    Route::put('/invoice/{invoice}', [InvoiceController::class, 'update'])->name('invoice.update');
    Route::delete('/invoice/{invoice}', [InvoiceController::class, 'destroy'])->name('invoice.destroy');
    Route::get('/invoice/{invoice}/print', [InvoiceController::class, 'print'])->name('invoice.print');
    Route::get('/invoice/{id}/email', [InvoiceController::class, 'emailInvoice'])->name('invoice.email');
    Route::post('/invoice/{invoice}/reminder', [InvoiceController::class, 'sendReminder'])
        ->name('invoice.reminder');


    Route::get('/reports/income', [ReportController::class, 'index'])->name('income');
    Route::post('/reports/generate-report', [ReportController::class, 'generateReport'])->name('generate-report');
    Route::post('/reports/generate-pdf', [ReportController::class, 'generatePdf'])->name('generate-pdf');

    Route::resource('/contacts', ContactController::class);
    Route::get('/contacts/search', [ContactController::class, 'search'])->name('contacts.search');
    Route::get('/contacts/{id}', [ContactController::class, 'getContact'])->name('contacts.get');


    Route::resource('templates', InvoiceTemplateController::class);
    Route::get('templates/{template}/preview', [InvoiceTemplateController::class, 'preview'])
        ->name('templates.preview');    Route::resource('invoiceitem', InvoiceItemController::class);

        Route::resource('permission', \App\Http\Controllers\PermissionController::class);
    /*Route::get('user', [UserController::class, 'index'])->name('user.index');*/
});

Route::controller(ImageUploadController::class)->group(function () {
    Route::get('/', 'index');
    Route::get('/previews', 'preview');
    Route::post('/upload', 'upload');
});


require __DIR__ . '/auth.php';
