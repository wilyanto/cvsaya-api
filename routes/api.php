<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Controllers\Api\v1\CvDocumentationsController;
use App\Http\Controllers\Api\v1\CvExpectedSalariesController;
use App\Http\Controllers\Api\v1\CvCertificationsController;
use App\Http\Controllers\Api\v1\CvExperiencesController;
use App\Http\Controllers\Api\v1\CvEducationsController;
use App\Http\Controllers\Api\v1\CvHobbiesController;
use App\Http\Controllers\Api\v1\CvSpecialitiesController;
use App\Http\Controllers\Api\v1\DepartmentsController;
use App\Http\Controllers\Api\v1\LevelController;
use App\Http\Controllers\Api\v1\PositionsController;
use App\Http\Controllers\Api\v1\CandidateEmployeesController;
use App\Http\Controllers\Api\v1\EmployeeDetailsController;
use App\Models\Certifications;
use App\Models\CvProfileDetail;

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
    Route::prefix('candidate')->group(function () {
        Route::controller(CandidateEmployeesController::class)->group(function () {
            Route::post('/', 'index');
            Route::post('create', 'addCandidateToBlast');
            // Route::post('update-status','updateStatus');
        });
    });

    Route::middleware('auth:api')->group(function () {
        Route::prefix('profile-detail')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/cv-page','cvDetail');
                Route::post('/',  'detail');
                Route::post('/add', 'store');
                Route::post('/update', 'update');
            });
        });

        Route::prefix('documents')->group(function () {
            Route::controller(CvDocumentationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'store');
            });
        });

        Route::prefix('department')->group(function () {
            Route::controller(DepartmentsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'create');
                Route::post('/update', 'update');
            });
        });


        Route::prefix('levels')->group(function () {
            Route::controller(LevelController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'create');
                Route::post('/update', 'update');
            });
        });

        Route::prefix('expected-salaries')->group(function () {
            Route::controller(CvExpectedSalariesController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'store');
            });
        });

        Route::prefix('positions')->group(function () {
            Route::controller(PositionsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'store');
                Route::post('/structure-organization', 'show');
                Route::post('/update', 'update');
            });
        });

        Route::prefix('educations')->group(function () {
            Route::controller(CvEducationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'add');
                Route::post('/delete', 'destroy');
                Route::post('/update',  'update');
            });
        });

        Route::prefix('experiences')->group(function () {
            Route::controller(CvExperiencesController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'add');
                Route::post('/update', 'update');
                Route::post('/delete',  'destroy');
            });
        });

        Route::prefix('empolyee')->group(function () {
            Route::controller(EmployeeDetailsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'create');
            });
        });

        Route::prefix('certifications')->group(function () {
            Route::controller(CvCertificationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'create');
                Route::post('/update', 'update');
                Route::post('/delete', 'destroy');
            });
        });

        Route::prefix('specialities')->group(function () {
            Route::controller(CvSpecialitiesController::class)->group(function () {
                Route::get('/',  'index');
                Route::get('/list','show');
                Route::get('/top-ten-list','showTopTenList');
                Route::post('/add', 'create');
                Route::post('/update-intergration', 'store');
                Route::post('/update',  'update');
                Route::post('/delete', 'destroy');
                Route::post('/delete-intergration', 'destroyIntergrity');
            });
        });


        Route::prefix('hobbies')->group(function () {
            Route::controller(CvHobbiesController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'create');
                Route::post('/delete', 'destroy');
                Route::post('/update', 'update');
            });
        });

        Route::prefix('sosmed')->group(function () {
            Route::get('/', [SosmedsController::class, 'index']);
            Route::post('/add', [SosmedsController::class, 'create']);
            Route::post('/update', [SosmedsController::class, 'update']);
            Route::post('/delete', [SosmedsController::class, 'destroy']);
        });
    });
});
