<?php

use App\Http\Controllers\AdminBrokerController;
use App\Http\Controllers\AdminBrokerMemoController;
use App\Http\Controllers\AdminClarityController;
use App\Http\Controllers\AdminColorController;
use App\Http\Controllers\AdminCompanyController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminCutController;
use App\Http\Controllers\AdminDesignationController;
use App\Http\Controllers\AdminDiamondController;
use App\Http\Controllers\AdminDiamondTransferController;
use App\Http\Controllers\AdminPolishController;
use App\Http\Controllers\AdminInvoiceController;
use App\Http\Controllers\AdminSaleDiamondsController;
use App\Http\Controllers\AdminShapeController;
use App\Http\Controllers\AdminSymmetryController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use Illuminate\Support\Facades\Artisan;
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
    return view('auth.login');
})->name('admin.login');


// // Route::post('/login', [LoginController::class, 'login']);
// Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('/register', [RegisterController::class, 'register']);
// Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('/password/email', [AdminController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('/password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::get('/password-reset', [AdminController::class, 'showResetForm'])->name('password.reset');
// Route::post('/password-reset', [AdminController::class, 'resetPassword'])->name('password.update');

// // Route::post('/password/reset', [ResetPasswordController::class, 'reset'])->name('password.update');
// // Route::get('/password/reset/{token}', [AdminController::class, 'showResetForm'])->name('password.reset');



//  for admin registration below comment uncomment karvi and above auth.login ne comment karvi
// Route::get('/', function () {
//     return view('welcome');
// });
// Auth::routes();

// Route::get('/logout', 'Auth\LoginController@logout');
Route::post('/login', [AdminController::class, 'login'])->name('login');
Route::get('/logout', [AdminController::class, 'logout'])->name('logout');

Route::group(['middleware' => ['auth', 'usersession']], function () {

    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin');
    Route::get('/admin/dashboard2', [AdminController::class, 'dashboard2'])->name('admin2');

    Route::get('/profile/{id}', [AdminController::class, 'profiledit'])->name('profile.edit');
    Route::post('/profile/update', [AdminController::class, 'profileUpdate'])->name('profile.update');

    Route::get("admin/company", [AdminCompanyController::class, 'index'])->name('admin.company.index');
    Route::get('admin/company/create', [AdminCompanyController::class, 'create'])->name('admin.company.create');
    Route::post('admin/company/store', [AdminCompanyController::class, 'store'])->name('admin.company.store');
    Route::get('admin/company/edit/{id}', [AdminCompanyController::class, 'edit'])->name('admin.company.edit');
    Route::patch('admin/company/update/{id}', [AdminCompanyController::class, 'update'])->name('admin.company.update');
    Route::get('admin/company/destroy/{id}', [AdminCompanyController::class, 'destroy'])->name('admin.company.destroy');

    Route::get("admin/designation", [AdminDesignationController::class, 'index'])->name('admin.designation.index');
    Route::get('admin/designation/show/{id}', [AdminDesignationController::class, 'show'])->name('admin.designation.show');
    Route::get('admin/designation/create', [AdminDesignationController::class, 'create'])->name('admin.designation.create');
    Route::post('admin/designation/store', [AdminDesignationController::class, 'store'])->name('admin.designation.store');
    Route::get('admin/designation/edit/{id}', [AdminDesignationController::class, 'edit'])->name('admin.designation.edit');
    Route::patch('admin/designation/update/{id}', [AdminDesignationController::class, 'update'])->name('admin.designation.update');
    Route::get('admin/designation/destroy/{id}', [AdminDesignationController::class, 'destroy'])->name('admin.designation.destroy');

    Route::get("admin/color", [AdminColorController::class, 'index'])->name('admin.color.index');
    Route::get('admin/color/create', [AdminColorController::class, 'create'])->name('admin.color.create');
    Route::post('admin/color/store', [AdminColorController::class, 'store'])->name('admin.color.store');
    Route::get('admin/color/edit/{id}', [AdminColorController::class, 'edit'])->name('admin.color.edit');
    Route::patch('admin/color/update/{id}', [AdminColorController::class, 'update'])->name('admin.color.update');
    Route::get('admin/color/destroy/{id}', [AdminColorController::class, 'destroy'])->name('admin.color.destroy');

    Route::get("admin/shape", [AdminShapeController::class, 'index'])->name('admin.shape.index');
    Route::get('admin/shape/create', [AdminShapeController::class, 'create'])->name('admin.shape.create');
    Route::post('admin/shape/store', [AdminShapeController::class, 'store'])->name('admin.shape.store');
    Route::get('admin/shape/edit/{id}', [AdminShapeController::class, 'edit'])->name('admin.shape.edit');
    Route::patch('admin/shape/update/{id}', [AdminShapeController::class, 'update'])->name('admin.shape.update');
    Route::get('admin/shape/destroy/{id}', [AdminShapeController::class, 'destroy'])->name('admin.shape.destroy');

    Route::get("admin/clarity", [AdminClarityController::class, 'index'])->name('admin.clarity.index');
    Route::get('admin/clarity/create', [AdminClarityController::class, 'create'])->name('admin.clarity.create');
    Route::post('admin/clarity/store', [AdminClarityController::class, 'store'])->name('admin.clarity.store');
    Route::get('admin/clarity/edit/{id}', [AdminClarityController::class, 'edit'])->name('admin.clarity.edit');
    Route::patch('admin/clarity/update/{id}', [AdminClarityController::class, 'update'])->name('admin.clarity.update');
    Route::get('admin/clarity/destroy/{id}', [AdminClarityController::class, 'destroy'])->name('admin.clarity.destroy');

    Route::get("admin/cut", [AdminCutController::class, 'index'])->name('admin.cut.index');
    Route::get('admin/cut/create', [AdminCutController::class, 'create'])->name('admin.cut.create');
    Route::post('admin/cut/store', [AdminCutController::class, 'store'])->name('admin.cut.store');
    Route::get('admin/cut/edit/{id}', [AdminCutController::class, 'edit'])->name('admin.cut.edit');
    Route::patch('admin/cut/update/{id}', [AdminCutController::class, 'update'])->name('admin.cut.update');
    Route::get('admin/cut/destroy/{id}', [AdminCutController::class, 'destroy'])->name('admin.cut.destroy');

    Route::get("admin/polish", [AdminPolishController::class, 'index'])->name('admin.polish.index');
    Route::get('admin/polish/create', [AdminPolishController::class, 'create'])->name('admin.polish.create');
    Route::post('admin/polish/store', [AdminPolishController::class, 'store'])->name('admin.polish.store');
    Route::get('admin/polish/edit/{id}', [AdminPolishController::class, 'edit'])->name('admin.polish.edit');
    Route::patch('admin/polish/update/{id}', [AdminPolishController::class, 'update'])->name('admin.polish.update');
    Route::get('admin/polish/destroy/{id}', [AdminPolishController::class, 'destroy'])->name('admin.polish.destroy');

    Route::get("admin/symmetry", [AdminSymmetryController::class, 'index'])->name('admin.symmetry.index');
    Route::get('admin/symmetry/create', [AdminSymmetryController::class, 'create'])->name('admin.symmetry.create');
    Route::post('admin/symmetry/store', [AdminSymmetryController::class, 'store'])->name('admin.symmetry.store');
    Route::get('admin/symmetry/edit/{id}', [AdminSymmetryController::class, 'edit'])->name('admin.symmetry.edit');
    Route::patch('admin/symmetry/update/{id}', [AdminSymmetryController::class, 'update'])->name('admin.symmetry.update');
    Route::get('admin/symmetry/destroy/{id}', [AdminSymmetryController::class, 'destroy'])->name('admin.symmetry.destroy');

    Route::post('admin/diamond/import', [AdminDiamondController::class, 'import'])->name('admin.diamond.import');
    Route::get('admin/diamond/import', [AdminDiamondController::class, 'importPage'])->name('diamond.import');

    Route::get("admin/diamonds", [AdminDiamondController::class, 'index'])->name('admin.diamonds.index');
    Route::get('/admin/diamond/add', [AdminDiamondController::class, 'create'])->name('admin.diamond.create');
    Route::post('admin/diamond/store', [AdminDiamondController::class, 'store'])->name('admin.diamond.store');
    Route::post('admin/diamond/update/{id}', [AdminDiamondController::class, 'update'])->name('admin.diamond.update');
    Route::get('admin/diamond/destroy/{id}', [AdminDiamondController::class, 'destroy'])->name('admin.diamond.destroy');
    Route::get('admin/diamond/edit/{id}', [AdminDiamondController::class, 'edit'])->name('admin.diamond.edit');
    Route::get("admin/all-diamonds", [AdminDiamondController::class, 'allDiamonds'])->name('admin.all.diamonds');
    Route::get('/admin/diamond/detail/{id}', [AdminDiamondController::class, 'diamondDetail']);
    Route::get("admin/print-image/{id}", [AdminDiamondController::class, 'printImage'])->name('admin.dimond.printimage');
    Route::post('/admin/diamond/bulk-print', [AdminDiamondController::class, 'bulkPrint'])->name('admin.diamond.bulk.print');


    Route::get('/admin/transfer/{type?}', [AdminDiamondTransferController::class, 'index'])->name('admin.diamond.transfer.index');
    Route::post('admin/transfer/store', [AdminDiamondTransferController::class, 'store'])
        ->name('admin.diamond.transfer.store');
    Route::get('admin/transfer/delete/{id}', [AdminDiamondTransferController::class, 'destroy'])->name('admin.diamond.transfer.delete');

    Route::get('/admin/diamond-transfer-search', [AdminDiamondTransferController::class, 'search']);

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/broker', [AdminBrokerController::class, 'index'])->name('broker.index');

        Route::get('/broker/create', [AdminBrokerController::class, 'create'])->name('broker.create');

        Route::post('/broker/store', [AdminBrokerController::class, 'store'])->name('broker.store');

        Route::get('/broker/edit/{id}', [AdminBrokerController::class, 'edit'])->name('broker.edit');

        Route::post('/broker/update/{id}', [AdminBrokerController::class, 'update'])->name('broker.update');

        Route::get('/broker/delete/{id}', [AdminBrokerController::class, 'destroy'])->name('broker.delete');
    });

    Route::prefix('admin')->name('admin.')->group(function () {

        Route::get('/broker-memo/{location}', [AdminBrokerMemoController::class, 'index'])
            ->name('broker.memo.index');
        Route::get('/broker-memo/{location}/create', [AdminBrokerMemoController::class, 'create'])->name('broker.memo.create');
        Route::post('/broker-memo/store', [AdminBrokerMemoController::class, 'store'])
            ->name('broker.memo.store');
        Route::get('/broker-memo/show/{id}', [AdminBrokerMemoController::class, 'show'])
            ->name('broker.memo.show');
        Route::post('/get-diamond-by-barcode', [AdminBrokerMemoController::class, 'getDiamond']);
        Route::get('/broker-memo/{id}/edit', [AdminBrokerMemoController::class, 'edit'])->name('broker.memo.edit');
        Route::post('/broker-memo/update/{id}', [AdminBrokerMemoController::class, 'update'])->name('broker.memo.update');
        Route::delete('/broker-memo/delete/{id}', [AdminBrokerMemoController::class, 'destroy'])->name('broker.memo.delete');
        Route::post('/broker-memo/remove-diamond', [AdminBrokerMemoController::class, 'removeDiamond'])->name('broker.memo.remove.diamond');
        Route::post('/broker-memo/add-diamond', [AdminBrokerMemoController::class, 'addDiamond']);
        Route::get('/broker-return/{location}', [AdminBrokerMemoController::class, 'returnPage'])->name('broker.memo.return');
        Route::post('/broker-memos', [AdminBrokerMemoController::class, 'getBrokerMemos'])
            ->name('broker.memo.list');
        Route::post('/get-memo-diamonds', [AdminBrokerMemoController::class, 'getMemoDiamonds']);
        Route::post('/return-diamond', [AdminBrokerMemoController::class, 'returnDiamond'])->name('broker.memo.return.diamond');
        Route::get('/broker-memo/print/{id}', [AdminBrokerMemoController::class, 'printMemo'])->name('broker.memo.print');


        Route::post('/sell-diamond', [AdminSaleDiamondsController::class, 'sellDiamond'])->name('broker.memo.sell.diamond');
        Route::post('/sales/update', [AdminSaleDiamondsController::class, 'update'])->name('sales.update');
        Route::get("/sell-diamonds", [AdminSaleDiamondsController::class, 'sellDiamondsList'])->name('sell.diamonds');
        Route::get('/owner-sale/{type?}', [AdminSaleDiamondsController::class, 'ownerSalePage'])->name('owner.sale');
        Route::get('/sales/delete/{id}', [AdminSaleDiamondsController::class, 'delete'])->name('sales.delete');

        Route::get('/invoices', [AdminInvoiceController::class, 'index'])->name('invoice.index');
        Route::get('/add-invoice', [AdminInvoiceController::class, 'add'])->name('add.invoice');
        Route::post('/invoice/add-diamond', [AdminInvoiceController::class, 'addDiamond']);
        Route::post('/invoice/store', [AdminInvoiceController::class, 'store'])->name('invoice.store');
        Route::get('/invoice/{id}/edit', [AdminInvoiceController::class, 'edit'])->name('invoice.edit');
        Route::post('/invoice/{id}/update', [AdminInvoiceController::class, 'update'])->name('invoice.update');
        Route::get('/invoice/preview/{id}', [AdminInvoiceController::class, 'preview']);
        Route::post('/invoice/{id}/update-client', [AdminInvoiceController::class, 'updateClient'])->name('invoice.updateClient');
        Route::post('/invoice/delete-item', [AdminInvoiceController::class, 'deleteItem']);
        Route::delete('/invoice/{id}/delete', [AdminInvoiceController::class, 'destroy'])->name('invoice.delete');
    });
    // Route::post('/get-sale-diamond', [AdminInvoiceController::class, 'getSaleDiamond']);
});

//Clear Cache facade value:
Route::get('/admin/clear-cache', function () {
    Artisan::call('cache:clear');
    return '<h1>Cache facade value cleared</h1>';
});

//Reoptimized class loader:
Route::get('/admin/optimize', function () {
    Artisan::call('optimize');
    return '<h1>Reoptimized class loader</h1>';
});

//Route cache:
Route::get('/admin/route-cache', function () {
    Artisan::call('route:cache');
    return '<h1>Routes cached</h1>';
});

//Clear Route cache:
Route::get('/admin/route-clear', function () {
    Artisan::call('route:clear');
    return '<h1>Route cache cleared</h1>';
});

//Clear View cache:
Route::get('/admin/view-clear', function () {
    Artisan::call('view:clear');
    return '<h1>View cache cleared</h1>';
});

//Clear Config cache:
Route::get('/admin/config-cache', function () {
    Artisan::call('config:cache');
    return '<h1>Clear Config cleared</h1>';
});
