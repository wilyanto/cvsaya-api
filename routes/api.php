<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Controllers\Api\v1\CvDocumentationController;
use App\Http\Controllers\Api\v1\CvExpectedJobsController;
use App\Http\Controllers\Api\v1\CvCertificationsController;
use App\Http\Controllers\Api\v1\CvExperiencesController;
use App\Http\Controllers\Api\v1\CvEducationsController;
use App\Http\Controllers\Api\v1\CvHobbiesController;
use App\Http\Controllers\Api\v1\CvSpecialitiesController;
use App\Http\Controllers\Api\v1\DepartmentsController;
use App\Http\Controllers\Api\v1\LevelController;
use App\Http\Controllers\Api\v1\PositionsController;
use App\Http\Controllers\Api\v1\CandidateController;
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

        Route::group(['middleware' => ['permission:manage-candidate|manage-schedule']], function () {
            Route::prefix('users')->group(function () {
                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/{id}/profile',  'getDetailByID');
                });

                Route::controller(CvExpectedJobsController::class)->group(function () {
                    Route::get('/{id}/expected-job', 'getIndexByID'); // path user/id/expected-jobs
                });

                Route::controller(CvDocumentationController::class)->group(function () {
                    Route::get('/{id}/document', 'index'); // path user/id/document
                });

                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/{id}/curriculum-vitae', 'cvDetailByID'); // path user/id/cv
                });

                Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                    Route::get('/{id}/interview-notes', 'showNote'); // path user/id/cv
                });
            });


            Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                Route::get('/character-traits', 'indexCharacterTraits');
            });

            Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                Route::get('/results', 'assessmentInterview');
            });

            Route::prefix('interviews')->group(function () {
                Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::get('/without-schedule', 'indexWithoutInterviewDate');
                    Route::get('/{id}', 'getDetail');
                    Route::put('/{id}/result', 'giveResult');
                    Route::put('/{id}', 'updateSchedule');
                });
            });

            Route::controller(CandidateEmpolyeeScheduleController::class)->group(function () {
                Route::get('/interviewers', 'indexInterviewer');
            });

            Route::group(['middleware' => ['permission:manage-candidate']], function () {
                Route::prefix('candidates')->group(function () {
                    Route::controller(CandidateController::class)->group(function () {
                        Route::get('/', 'index');
                        Route::get('/{id}', 'indexDetail');
                        Route::get('/positions', 'getPosition');
                        Route::post('/', 'addCandidateToBlast');
                        Route::put('/{id}', 'updateStatus');
                        // Route::post('update-status','updateStatus');

                    });
                });
            });
        });

        Route::prefix('profile')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/',  'getDetailByDefault');
                Route::post('/', 'store');
                Route::put('/', 'update');
            });
        });

        Route::prefix('completeness-status')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/', 'status');
            });
        });

        Route::prefix('expected-job')->group(function () {
            Route::controller(CvExpectedJobsController::class)->group(function () {
                Route::get('/', 'getIndexByDefault');
                Route::post('/', 'storeOrUpdate');
            });
        });

        Route::prefix('document')->group(function () {
            Route::controller(CvDocumentationController::class)->group(function () {
                Route::get('/', 'getByDefault');
                Route::post('/upload', 'uploadStorage');
                Route::post('/', 'store');
            });
        });

        Route::prefix('curriculum-vitae')->group(function () {
            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/', 'cvDetailByDefault');
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
                Route::get('/suggestions', 'suggestion');
                Route::post('/', 'create');
                Route::delete('/{id}', 'destroy');
                Route::put('/{id}', 'update');
            });
        });

        Route::prefix('specialities')->group(function () {
            Route::controller(CvSpecialitiesController::class)->group(function () {
                Route::get('/',  'index');
                Route::get('/suggestions', 'suggestion');
                Route::post('/', 'create');
                Route::put('/{id}/certificates', 'updateCertificate');
                Route::put('/{id}',  'update');
                Route::delete('/{id}', 'destroy');
            });
        });

        Route::prefix('permissions')->group(function () {
            Route::controller(PermissionController::class)->group(function () {
                Route::get('/', 'getPermission');
            });
        });




        Route::prefix('departments')->group(function () {
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


        Route::prefix('degrees')->group(function () {
            Route::controller(CvEducationsController::class)->group(function () {
                Route::get('/', 'degreeList');
            });
        });

        Route::prefix('employees')->group(function () {
            Route::controller(EmployeeDetailsController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'create');
            });
            Route::controller(EmploymentTypeController::class)->group(function () {
                Route::get('/types', 'index');
            });
        });
    });

    //master
    Route::prefix('candidate-positions')->group(function () {
        Route::controller(CvExpectedJobsController::class)->group(function () {
            Route::get('/', 'getListCandidatePositions');
            Route::post('/', 'createCandidatePositions');
            Route::put('/{id}', 'updateVerfiedCandidatePositions');
        });
    });

    Route::prefix('religions')->group(function () {
        Route::controller(ReligionController::class)->group(function () {
            Route::get('/', 'index');
        });
    });

    Route::prefix('marriage-statuses')->group(function () {
        Route::controller(MarriageStatusController::class)->group(function () {
            Route::get('/', 'index');
        });
    });
});
