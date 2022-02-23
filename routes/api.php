<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Controllers\Api\v1\CvDocumentationsController;
use App\Http\Controllers\Api\v1\CvExpectedSalariesController;
use App\Http\Controllers\Api\v1\CertificationsController;
use App\Http\Controllers\Api\v1\ExperiencesController;
use App\Http\Controllers\Api\v1\EducationsController;
use App\Http\Controllers\Api\v1\HobbiesController;
use App\Http\Controllers\Api\v1\SosmedsController;
use App\Http\Controllers\Api\v1\SpecialitiesController;
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
            Route::controller(DepartmentsController::class)->group(function(){
                Route::get('/','index');
                Route::post('/add','create');
                Route::post('/update','update');
            });
        });


        Route::prefix('levels')->group(function () {
            Route::controller(LevelController::class)->group(function(){
                Route::get('/','index');
                Route::post('/add','create');
                Route::post('/update','update');

            });
        });

        Route::prefix('expected-salaries')->group(function(){
            Route::controller(CvExpectedSalariesController::class)->group(function(){
               Route::get('/','index');
               Route::post('/create','store');
            });
        });

        Route::prefix('positions')->group(function () {
            Route::controller(PositionsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/add', 'store');
                Route::post('/structure-organization','show');
                Route::post('/update','update');
            });
        });

        Route::prefix('empolyee')->group(function () {
            Route::controller(EmployeeDetailsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'create');
            });
        });

        Route::prefix('certifications')->group(function () {
            Route::controller(CertificationsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/create', 'create');
                Route::post('/update', 'update');
                Route::post('/delete', 'destroy');
            });
        });

        Route::prefix('experiences')->group(function () {
            Route::get('/', [ExperiencesController::class, 'index']);
            Route::post('/add', [ExperiencesController::class, 'add']);
            Route::post('/update', [ExperiencesController::class, 'update']);
            Route::post('/delete', [ExperiencesController::class, 'destroy']);
        });

        Route::prefix('educations')->group(function () {
            Route::get('/', [EducationsController::class, 'index']);
            Route::post('/add', [EducationsController::class, 'add']);
            Route::post('/delete', [EducationsController::class, 'destroy']);
            Route::post('/update', [EducationsController::class, 'update']);
        });

        Route::prefix('hobbies')->group(function () {
            Route::get('/', [HobbiesController::class, 'index']);
            Route::post('/add', [HobbiesController::class, 'create']);
            Route::post('/delete', [HobbiesController::class, 'destroy']);
            Route::post('/update', [HobbiesController::class, 'update']);
        });

        Route::prefix('sosmed')->group(function () {
            Route::get('/', [SosmedsController::class, 'index']);
            Route::post('/add', [SosmedsController::class, 'create']);
            Route::post('/update', [SosmedsController::class, 'update']);
            Route::post('/delete', [SosmedsController::class, 'destroy']);
        });


        Route::prefix('specialities')->group(function () {
            Route::get('/', [SpecialitiesController::class, 'index']);
            Route::post('/add', [SpecialitiesController::class, 'create']);
            Route::post('/update-intergration', [SpecialitiesController::class, 'store']);
            Route::post('/update', [SpecialitiesController::class, 'update']);
            Route::post('/delete', [SpecialitiesController::class, 'destroy']);
        });
    });
});
