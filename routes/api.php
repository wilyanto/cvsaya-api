<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\MRI\ActivityController;
use App\Http\Controllers\Api\v1\MRI\AppointmentController;
use App\Http\Controllers\Api\v1\MRI\FeedbackController;
use App\Http\Controllers\Api\v1\Home\VoucherController;
use App\Http\Controllers\Api\v1\Home\BannerController;
use App\Http\Controllers\Api\v1\Customer\AuthController;
use App\Http\Controllers\Api\v1\BeautyTreatments\BeautyTreatmentCategoryController;
use App\Http\Controllers\Api\v1\BeautyTreatments\BeautyTreatmentOutletController;
use App\Http\Controllers\Api\v1\BeautyTreatments\BeautyTreatmentController;
use App\Http\Controllers\Api\v1\BeautyTreatments\BeautyTreatmentCartController;
use App\Http\Controllers\Api\v1\Transaction\HistoryController;
use App\Http\Controllers\Api\v1\MRI\Invoice\OrderController;
use App\Http\Controllers\Api\v1\Transaction\OrderMasterTransaksiController;
use App\Http\Controllers\Api\v1\AdminController;
use App\Http\Controllers\EnakApi\v1\EnakCartController;
use App\Http\Controllers\EnakApi\v1\EnakProductCategoryController;
use App\Http\Controllers\EnakApi\v1\EnakMerchantController;
use App\Http\Controllers\EnakApi\v1\EnakOutletController;
use App\Http\Controllers\EnakApi\v1\EnakProductController;
use App\Http\Controllers\CvSaya\CvSayaUserProfileDetailController;
use App\Http\Controllers\CvSaya\CvSayaCertificationsController;
use App\Http\Controllers\CvSaya\CvSayaExperiencesController;
use App\Http\Controllers\CvSaya\CvSayaEducationsController;
use App\Http\Controllers\CvSaya\CvSayaHobbiesController;
use App\Http\Controllers\CvSaya\CvSayaSosmedsController;
use App\Http\Controllers\CvSaya\CvSayaSpecialitiesController;
use App\Http\Controllers\CvSaya\CvSayaDepartmentsController;
use App\Http\Controllers\CvSaya\CvSayaLevelController;
use App\Http\Controllers\CvSaya\CvSayaPositionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::prefix('customer')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login']);
        Route::post('get-otp', [AuthController::class, 'otpRequest']);

        Route::group(['middleware' => 'auth:api'], function () {
            Route::post('rename', [AuthController::class, 'changeName']);
            Route::get('profile', [AuthController::class, 'profile']);
            Route::post('subscribe', [AuthController::class, 'changeEmail']);
            Route::post('change-birthdate', [AuthController::class, 'changeBirthdate']);
            Route::post('change-gender', [AuthController::class, 'changeGender']);

        });
    });

    Route::prefix('beauty-treatments')->group(function () {
        Route::get('/', [BeautyTreatmentController::class, 'getAllTreatments']);
        Route::get('by-name', [BeautyTreatmentController::class, 'getAllTreatmentsByName']);
        Route::get('categories', [BeautyTreatmentCategoryController::class, 'getTreatmentCategories']);
        Route::get('outlets-by-category', [BeautyTreatmentOutletController::class, 'getOutletsByCategory']);
        Route::get('by-outlet', [BeautyTreatmentController::class, 'getAllTreatmentsByOutlet']);
    });

    Route::resource('banner', BannerController::class);

    Route::prefix('voucher')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('claimed', [VoucherController::class, 'getUsersVouchers']);
            Route::post('claim', [VoucherController::class, 'createVoucher']);
        });
        Route::get('listing', [VoucherController::class, 'getListingVouchers']);
        Route::get('details', [VoucherController::class, 'getVoucherDetails']);
    });

    Route::prefix('cart')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('/', [BeautyTreatmentCartController::class, 'getAllCarts']);
            Route::get('beauty-treatment', [BeautyTreatmentCartController::class, 'getAllTreatmentsFromCart']);
            Route::post('beauty-treatment/update', [BeautyTreatmentCartController::class, 'updateCart']);
            Route::post('beauty-treatment/remove', [BeautyTreatmentCartController::class, 'removeItemsFromCart']);
        });
    });


    Route::prefix('transaction')->group(function () {
        Route::get('history/details', [HistoryController::class, 'historyDetails']);

        Route::resource('history', HistoryController::class);
    });

    Route::prefix('mri')->group(function () {
        Route::middleware('auth:api')->group(function () {
            Route::get('/invoice/validation', [OrderController::class, 'getAllOrders']);
        });

        Route::prefix('feedback')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::post('/submit', [FeedbackController::class, 'feedback']);
                Route::get('show-review', [FeedbackController::class, 'showReview']);
            });
        });

        Route::prefix('history')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::get('activity', [HistoryController::class, 'showActivities']);
                Route::get('waiting', [HistoryController::class, 'waiting']);
                Route::get('in-service', [HistoryController::class, 'inService']);
                Route::get('finished', [HistoryController::class, 'finished']);
                Route::get('finished-details', [HistoryController::class, 'finishedDetails']);
                Route::get('cancelled', [HistoryController::class, 'cancelled']);
            });
        });

        Route::prefix('appointment')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::post('use-now', [AppointmentController::class, 'useTreatment']);
                Route::post('set-schedule', [AppointmentController::class, 'setSchedule']);
                Route::post('change-schedule', [AppointmentController::class, 'changeSchedule']);
                Route::get('available-slots', [AppointmentController::class, 'showAvailableSlots']);
                Route::get('show-schedule', [AppointmentController::class, 'showSchedule']);
            });
        });

        Route::prefix('purchased')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::get('treatments', [OrderController::class, 'showPurchases']);
            });
        });

        Route::prefix('transaction')->group(function () {
            Route::middleware('auth:api')->group(function () {
                Route::get('validation', [OrderController::class, 'validation']);
                Route::get('payment-methods', [OrderMasterTransaksiController::class, 'getAllPaymentMethods']);
                Route::get('referals', [AdminController::class, 'getAllReferals']);
                Route::post('create-invoice', [OrderController::class, 'createInvoice']);
                Route::get('invoice-details', [OrderController::class, 'invoiceDetails']);
                Route::post('change-payment-method',[OrderController::class, 'changePaymentMethod']);
                Route::post('cancel', [OrderController::class, 'cancel']);
            });
        });
    });

    Route::prefix('enak')->group(function () {
        Route::prefix('merchant-categories')->group(function () {
            Route::get('/', [EnakProductCategoryController::class, 'getAllMerchantCategories']);
        });

        Route::prefix('product-categories')->group(function () {
            Route::get('by-outlet', [EnakProductCategoryController::class, 'getAllCategoriesFromOutlet']);
        });

        Route::get('merchants', [EnakMerchantController::class, 'getAllMerchants']);

        Route::prefix('products')->group(function () {
            Route::get('by-category', [EnakProductController::class, 'getProductsByCategory']);
        });

        Route::prefix('outlets')->group(function () {
            Route::get('/', [EnakOutletController::class, 'getAllOutlets']);
            Route::get('search', [EnakOutletController::class, 'searchOutlet']);
            Route::get('by-category', [EnakOutletController::class, 'getOutletByCategory']);
            Route::get('detail', [EnakOutletController::class, 'getOutletDetail']);

            Route::prefix('cart')->group(function () {
                Route::get('products', [EnakCartController::class, 'getAllProductsFromCart']);
            });
        });
    });

    Route::prefix('cvsaya')->group(function(){
        Route::middleware('auth:api')->group(function(){
            Route::prefix('user-profile-detail')->group(function(){
                Route::post('/',[CvSayaUserProfileDetailController::class,'detail']);
                Route::post('/create',[CvSayaUserProfileDetailController::class,'create']);
                Route::post('/update',[CvSayaUserProfileDetailController::class,'update']);
            });
            Route::prefix('certifications')->group(function(){
                Route::get('/',[CvSayaCertificationsController::class,'index']);
                Route::post('/create',[CvSayaCertificationsController::class,'create']);
                Route::post('/update',[CvSayaCertificationsController::class,'update']);
                Route::post('/delete',[CvSayaCertificationsController::class,'destroy']);
            });

            Route::prefix('experiences')->group(function(){
                Route::get('/',[CvSayaExperiencesController::class,'index']);
                Route::post('/add',[CvSayaExperiencesController::class,'add']);
                Route::post('/update',[CvSayaExperiencesController::class,'update']);
                Route::post('/delete',[CvSayaExperiencesController::class,'destroy']);
            });

            Route::prefix('educations')->group(function(){
                Route::get('/',[CvSayaEducationsController::class,'index']);
                Route::post('/add',[CvSayaEducationsController::class,'add']);
                Route::post('/update',[CvSayaEducationsController::class,'update']);
                Route::post('/delete',[CvSayaEducationsController::class,'destroy']);
            });

            Route::prefix('hobbies')->group(function(){
                Route::get('/',[CvSayaHobbiesController::class,'index']);
                Route::post('/add',[CvSayaHobbiesController::class,'create']);
                Route::post('/update',[CvSayaHobbiesController::class,'update']);
                Route::post('/delete',[CvSayaHobbiesController::class,'destroy']);
            });

            Route::prefix('sosmed')->group(function(){
                Route::get('/',[CvSayaSosmedsController::class,'index']);
                Route::post('/add',[CvSayaSosmedsController::class,'create']);
                Route::post('/update',[CvSayaSosmedsController::class,'update']);
                Route::post('/delete',[CvSayaSosmedsController::class,'destroy']);
            });


            Route::prefix('specialities')->group(function(){
                Route::get('/',[CvSayaSpecialitiesController::class,'index']);
                Route::post('/add',[CvSayaSpecialitiesController::class,'create']);
                Route::post('/update-intergration',[CvSayaSpecialitiesController::class,'store']);
                Route::post('/update',[CvSayaSpecialitiesController::class,'update']);
                Route::post('/delete',[CvSayaSpecialitiesController::class,'destroy']);
            });

            Route::prefix('department')->group(function(){
                Route::post('/',[CvSayaDepartmentsController::class,'index']);
                Route::post('/add',[CvSayaDepartmentsController::class,'create']);
                Route::post('/update',[CvSayaDepartmentsController::class,'update']);
            });


            Route::prefix('levels')->group(function(){
                Route::post('/',[CvSayaLevelController::class,'index']);
                Route::post('/add',[CvSayaLevelController::class,'create']);
                Route::post('/update',[CvSayaLevelController::class,'update']);;
            });

            Route::prefix('positions')->group(function(){
                Route::post('/',[CvSayaPositionsController::class,'index']);
                Route::post('/add',[CvSayaPositionsController::class,'store']);
                Route::post('/structure-organization',[CvSayaPositionsController::class,'show']);
                Route::post('/update',[CvSayaPositionsController::class,'update']);;
            });
        });
    });
});
