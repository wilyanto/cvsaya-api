<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Controllers\Api\v1\CvDocumentationController;
use App\Http\Controllers\Api\v1\CvExpectedPositionsController;
use App\Http\Controllers\Api\v1\CvCertificationsController;
use App\Http\Controllers\Api\v1\CvExperiencesController;
use App\Http\Controllers\Api\v1\CvEducationsController;
use App\Http\Controllers\Api\v1\CvHobbiesController;
use App\Http\Controllers\Api\v1\CvSpecialitiesController;
use App\Http\Controllers\Api\v1\DepartmentsController;
use App\Http\Controllers\Api\v1\LevelController;
use App\Http\Controllers\Api\v1\PositionsController;
use App\Http\Controllers\Api\v1\CandidateEmployeeController;
use App\Http\Controllers\Api\v1\EmployeeDetailsController;
use App\Http\Controllers\Api\v1\CandidateEmpolyeeScheduleController;
use App\Http\Controllers\Api\v1\CompanyController;
use App\Http\Controllers\Api\v1\EmploymentTypeController;
use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Controllers\Api\v1\ReligionController;
use App\Http\Controllers\Api\v1\MarriageStatusController;
use App\Models\Certifications;
use App\Models\CvProfileDetail;
use App\Models\EmploymentType;

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

    Route::middleware('auth:api')->group(function () {
        Route::prefix('companies')->group(function () {
            Route::controller(CompanyController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::put('/{id}', 'update');
            });
        });

        Route::prefix('religions')->group(function () {
            Route::controller(ReligionController::class)->group(function () {
                Route::get('/', 'index');
            });
        });

        Route::prefix('marriage/status')->group(function () {
            Route::controller(MarriageStatusController::class)->group(function () {
                Route::get('/', 'index');
            });
        });

        Route::prefix('profiles')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/status', 'status');
                Route::get('/',  'detail');
                Route::post('/', 'store');
                Route::put('/', 'update');
            });
        });

        Route::prefix('expected-job')->group(function () {
            Route::controller(CvExpectedPositionsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'storeOrUpdate');
            });
        });

        Route::prefix('candidate-positions')->group(function () {
            Route::controller(CvExpectedPositionsController::class)->group(function () {
                Route::get('/', 'getListCandidatePositions');
                Route::post('/', 'createCandidatePositions');
                Route::put('/{id}', 'updateVerfiedCandidatePositions');
            });
        });

        Route::prefix('documents')->group(function () {
            Route::controller(CvDocumentationController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/upload', 'uploadStorage');
                Route::post('/', 'store');
            });
        });

        Route::prefix('cv')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/', 'cvDetail');
            });
        });

        Route::prefix('certifications')->group(function () {
            Route::controller(CvCertificationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'create');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
            });
        });

        Route::prefix('educations')->group(function () {
            Route::controller(CvEducationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/degree', 'degreeList');
                Route::post('/', 'add');
                Route::delete('/{id}', 'destroy');
                Route::put('/{id}',  'update');
            });
        });

        Route::prefix('experiences')->group(function () {
            Route::controller(CvExperiencesController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'add');
                Route::put('/{ip}', 'update');
                Route::delete('/{ip}',  'destroy');
            });
        });

        Route::prefix('hobbies')->group(function () {
            Route::controller(CvHobbiesController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/list', 'show');
                Route::get('/top-ten-list', 'showTopTenList');
                Route::post('/', 'create');
                Route::delete('/{id}', 'destroy');
                Route::put('/{id}', 'update');
            });
        });

        Route::prefix('specialities')->group(function () {
            Route::controller(CvSpecialitiesController::class)->group(function () {
                Route::get('/',  'index');
                Route::get('/list', 'show');
                Route::get('/top-ten-list', 'showTopTenList');
                Route::post('/', 'create');
                Route::put('/intergration/{id}', 'updateCertificate');
                Route::put('/{id}',  'update');
                Route::delete('/{id}', 'destroy');
            });
        });

        Route::prefix('permission')->group(function () {
            Route::controller(PermissionController::class)->group(function () {
                Route::get('/', 'getPermission');
            });
        });


        Route::prefix('candidate')->group(function () {
            Route::controller(CandidateEmployeeController::class)->group(function () {
                Route::prefix('interview')->group(function () {
                    Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                        Route::get('/', 'index');
                        Route::get('/date', 'indexByDate');
                        Route::get('/{id}', 'getDetail');
                        Route::put('/{id}/result', 'giveResult');
                        Route::put('/{id}', 'updateSchedulue');
                    });
                });
                Route::get('/', 'index');
                Route::get('/positions', 'getPosition');
                Route::get('/{id}', 'indexDetail');
                Route::post('/', 'addCandidateToBlast');
                Route::put('/{id}', 'updateStatus');
                // Route::post('update-status','updateStatus');

            });
        });



        Route::prefix('department')->group(function () {
            Route::controller(DepartmentsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'create');
                Route::put('/{id}', 'update');
            });
        });


        Route::prefix('levels')->group(function () {
            Route::controller(LevelController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'create');
                Route::put('/{id}', 'update');
            });
        });


        Route::prefix('positions')->group(function () {
            Route::controller(PositionsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('/structure-organization', 'show');
                Route::post('/{id}', 'update');
            });
        });



        Route::prefix('employee')->group(function () {
            Route::controller(EmployeeDetailsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'create');
            });
            Route::controller(EmploymentTypeController::class)->group(function () {
                Route::get('/type', 'index');
            });
        });
    });
});
