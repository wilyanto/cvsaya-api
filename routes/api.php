<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use App\Http\Controllers\Api\v1\CvDocumentController;
use App\Http\Controllers\Api\v1\CvExpectedJobController;
use App\Http\Controllers\Api\v1\CvCertificationController;
use App\Http\Controllers\Api\v1\CvExperienceController;
use App\Http\Controllers\Api\v1\CvEducationController;
use App\Http\Controllers\Api\v1\CvHobbyController;
use App\Http\Controllers\Api\v1\CvSpecialityController;
use App\Http\Controllers\Api\v1\DepartmentController;
use App\Http\Controllers\Api\v1\LevelController;
use App\Http\Controllers\Api\v1\PositionController;
use App\Http\Controllers\Api\v1\CandidateController;
use App\Http\Controllers\Api\v1\EmployeeController;
use App\Http\Controllers\Api\v1\CandidateInterviewScheduleController;
use App\Http\Controllers\Api\v1\CompanyController;
use App\Http\Controllers\Api\v1\EmploymentTypeController;
use App\Http\Controllers\Api\v1\PermissionController;
use App\Http\Controllers\Api\v1\ReligionController;
use App\Http\Controllers\Api\v1\MarriageStatusController;
use App\Http\Controllers\Api\v1\SalaryTypeController;
use App\Http\Controllers\Api\v1\AttendanceController;
use App\Http\Controllers\Api\v1\BlastController;
use App\Http\Controllers\Api\v1\CandidateNoteController;
use App\Http\Controllers\Api\v1\ShiftController;
use App\Http\Controllers\Api\v1\CandidatePositionController;
use App\Http\Controllers\Api\v1\AttendanceQrCodeController;
use App\Http\Controllers\Api\v1\EmployeeOneTimeShiftController;
use App\Http\Controllers\Api\v1\EmployeeRecurringShiftController;
use App\Http\Controllers\Api\v1\EmployeeShiftController;
use App\Http\Controllers\Api\v1\LeavePermissionController;
use App\Http\Controllers\Api\v1\LeavePermissionOccasionController;
use App\Http\Controllers\Api\v1\BlastTypeController;
use App\Http\Controllers\Api\v1\BlastTypeRuleController;
use App\Http\Controllers\Api\v1\CompanySalaryTypeController;
use App\Http\Controllers\Api\v1\CRMCredentialController;
use App\Http\Controllers\Api\v1\EmployeeAdHocController;
use App\Http\Controllers\Api\v1\EmployeeBankAccountController;
use App\Http\Controllers\Api\v1\EmployeePayslipAdHocController;
use App\Http\Controllers\Api\v1\EmployeeResignationController;
use App\Http\Controllers\Api\v1\PayrollController;
use App\Http\Controllers\Api\v1\PayrollPeriodController;
use App\Http\Controllers\Api\v1\PayslipController;
use App\Http\Controllers\Api\v1\QuotaTypeController;

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
    Route::apiResource('attendance-qr-codes', AttendanceQrCodeController::class);
    Route::apiResource('shifts', ShiftController::class);

    Route::middleware('auth:api')->group(function () {
        Route::get('crm-credentials/{id}/blast-logs', [CRMCredentialController::class, 'getBlastLogs']);
        Route::patch('crm-credentials/{id}/status', [CRMCredentialController::class, 'updateStatus']);
        Route::get('crm-credentials/{id}/sync-quotas', [CRMCredentialController::class, 'syncCredentialQuota']);
        Route::get('crm-credentials/{id}/sync-credential', [CRMCredentialController::class, 'syncCredential']);
        Route::get('crm-credentials/{id}/blast-type', [CRMCredentialController::class, 'getBlastTypes']);
        Route::get('crm-credentials/blast-count', [CRMCredentialController::class, 'indexForReport']);
        Route::get('crm-credentials/{id}/blast-count', [CRMCredentialController::class, 'showForReport']);
        Route::patch('crm-credentials/{id}/blast-type', [CRMCredentialController::class, 'updateBlastTypes']);
        Route::patch('blast-types/reorder-priority', [BlastTypeController::class, 'reorderPriority']);
        Route::patch('quota-types/reorder-priority', [QuotaTypeController::class, 'reorderPriority']);
        Route::get('me/shift', [ShiftController::class, 'getShift']);
        Route::get('me/attendance-histories', [AttendanceController::class, 'getAttendancesByDateRange']);
        Route::get('me/attendance-schedule', [EmployeeShiftController::class, 'getShift']);
        Route::put('me/update-name', [CandidateController::class, 'updateCandidateName']);
        Route::post('me/update-profile-picture', [CandidateController::class, 'updateProfilePicture']);

        Route::apiResource('employee-recurring-shifts', EmployeeRecurringShiftController::class);
        Route::apiResource('crm-credentials', CRMCredentialController::class, ['only' => ['index', 'show', 'store', 'update']]);
        Route::apiResource('blast-type-rules', BlastTypeRuleController::class);
        Route::apiResource('blast-types', BlastTypeController::class);
        Route::apiResource('quota-types', QuotaTypeController::class);
        Route::apiResource('payroll-periods', PayrollPeriodController::class);

        Route::prefix('companies')->group(function () {
            Route::get('/{id}/resignations', [EmployeeResignationController::class, 'showResignationsByCompany']);
            Route::get('/{id}/payrolls', [PayrollController::class, 'indexByCompanyId']);
            Route::get('/{id}/payroll-periods', [PayrollPeriodController::class, 'indexByCompanyId']);
            Route::controller(CompanyController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::put('/{id}', 'update');
            });
            Route::controller(ShiftController::class)->group(function () {
                Route::get('/{companyId}/shifts', 'getShiftsByCompany');
            });
            Route::controller(PositionController::class)->group(function () {
                Route::get('/{companyId}/positions', 'getPositionsByCompany');
            });
            Route::controller(EmployeeController::class)->group(function () {
                Route::get('/{companyId}/employees', 'getEmployeesByCompany');
                Route::get('/{companyId}/employees-report', 'indexForReport');
            });
        });

        Route::group(['middleware' => ['permission:manage-employee']], function () {
            Route::apiResource('employee-one-time-shifts', EmployeeOneTimeShiftController::class);
            Route::apiResource('employee-recurring-shifts', EmployeeRecurringShiftController::class);
            Route::apiResource('employee-resignations', EmployeeResignationController::class);
            Route::apiResource('employee-bank-accounts', EmployeeBankAccountController::class);
            Route::apiResource('employee-payrolls', PayrollController::class);
            Route::apiResource('employee-ad-hocs', EmployeeAdHocController::class);
            Route::apiResource('company-salary-types', CompanySalaryTypeController::class);
            Route::apiResource('employee-payslips/{payslipId}/ad-hoc', EmployeePayslipAdHocController::class);
            Route::apiResource('employee-payslips', PayslipController::class);
            Route::patch('employee-payslips/{id}/generate', [PayslipController::class, 'generatePayslip']);
            Route::patch('employee-payslips/{id}/pay', [PayslipController::class, 'payPayslip']);
            Route::patch('employee-payslips/{id}/generate-and-pay', [PayslipController::class, 'generateAndPayPayslip']);
            Route::patch('employee-resignations/{id}/status', [EmployeeResignationController::class, 'updateEmployeeResignationStatus']);
            Route::controller(EmployeeRecurringShiftController::class)->group(function () {
                Route::get('employees/{employeeId}/recurring-shifts', 'getEmployeeRecurringShifts');
            });
            Route::apiResource('employee-shifts', EmployeeShiftController::class);
        });

        Route::group(['middleware' => ['permission:manage-candidate|manage-schedule']], function () {
            Route::prefix('admin')->group(function () {
                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/profile', 'show');
                });
            });

            Route::prefix('candidates')->group(function () {
                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/{id}/profile',  'indexDetail');
                });
                Route::controller(CvExpectedJobController::class)->group(function () {
                    Route::get('/{id}/expected-job', 'show'); // path user/id/expected-jobs
                });
                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/{id}/curriculum-vitae', 'getCandidateCv'); // path user/id/cv
                });
                Route::controller(CandidateInterviewScheduleController::class)->group(function () {
                    Route::get('/{id}/interview-notes', 'showNote'); // path user/id/cv
                });
                Route::controller(CandidateInterviewScheduleController::class)->group(function () {
                    Route::get('/{id}/interviews', 'getDetail'); // path user/id/cv
                });
                Route::controller(CvDocumentController::class)->group(function () {
                    Route::get('/{id}/documents', 'show'); // path user/id/cv
                });
                Route::group(['middleware' => ['permission:manage-candidate']], function () {
                    Route::controller(CandidateNoteController::class)->group(function () {
                        Route::post('/{id}/candidate-notes', 'storeCandidateNotes');
                        Route::get('/{id}/candidate-notes', 'getCandidateNotes');
                    });
                });
            });

            Route::controller(CandidateInterviewScheduleController::class)->group(function () {
                Route::get('/character-traits', 'indexCharacterTraits');
                Route::get('/results', 'assessmentInterview');
            });

            Route::prefix('interviews')->group(function () {
                Route::controller(CandidateInterviewScheduleController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::get('/without-schedule', 'indexWithoutInterviewDate');
                    Route::put('/{id}/result', 'giveResult');
                    Route::put('/{id}', 'updateSchedule');
                    Route::put('/{id}/reject', 'rejectInterview');
                });
            });

            Route::controller(CandidateInterviewScheduleController::class)->group(function () {
                Route::get('/interviewers', 'indexInterviewer');
            });

            Route::group(['middleware' => ['permission:manage-candidate']], function () {
                Route::prefix('candidates')->group(function () {
                    Route::controller(CandidateController::class)->group(function () {
                        Route::get('/', 'index');
                        Route::get('/candidate-summary', 'getSummaryByDay');
                        Route::get('/{id}', 'indexDetail');
                        Route::put('/{id}', 'updateStatus');
                        Route::post('/{id}/interviews', 'addSchedule');
                    });
                });
                Route::prefix('candidate-positions')->group(function () {
                    Route::controller(CandidateController::class)->group(function () {
                        Route::get('/statistic', 'getPosition');
                        Route::get('/uncategorized-statistic', 'getUncategorizedPosition');
                    });
                });
            });
        });

        Route::prefix('documents')->group(function () {
            Route::controller(CvDocumentController::class)->group(function () {
                Route::get('/type', 'getDocumentByID');
                Route::post('/', 'upload');
                Route::group(['middleware' => 'throttle:1000,60'], function () {
                    Route::get('/{documentID}', 'showById');
                });
            });
        });

        Route::prefix('document-types')->group(function () {
            Route::controller(CvDocumentController::class)->group(function () {
                Route::get('/type', 'getDocumentByID');
            });
        });

        Route::prefix('cv')->group(function () {
            Route::prefix('profile')->group(function () {
                Route::controller(CvProfileDetailController::class)->group(function () {
                    Route::get('/',  'index');
                    Route::post('/', 'store');
                    Route::put('/', 'update');
                });
            });

            Route::prefix('completeness-status')->group(function () {
                Route::controller(CandidateController::class)->group(function () {
                    Route::get('/', 'getCompletenessStatus');
                });
            });

            Route::prefix('expected-job')->group(function () {
                Route::controller(CvExpectedJobController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'storeOrUpdate');
                });
            });

            Route::prefix('documents')->group(function () {
                Route::controller(CvDocumentController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                });
            });

            Route::controller(CvProfileDetailController::class)->group(function () {
                Route::get('/', 'cvDetailByDefault');
            });

            Route::prefix('certificates')->group(function () {
                Route::controller(CvCertificationController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            Route::prefix('educations')->group(function () {
                Route::controller(CvEducationController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::put('/{id}',  'update');
                    Route::delete('/{id}', 'destroy');
                });
            });

            Route::prefix('experiences')->group(function () {
                Route::controller(CvExperienceController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::put('/{id}', 'update');
                    Route::delete('/{id}',  'destroy');
                });
            });

            Route::prefix('hobbies')->group(function () {
                Route::controller(CvHobbyController::class)->group(function () {
                    Route::get('/', 'index');
                    Route::get('/suggestions', 'suggestion');
                    Route::post('/', 'store');
                    Route::delete('/{id}', 'destroy');
                    Route::put('/{id}', 'update');
                });
            });

            Route::prefix('specialities')->group(function () {
                Route::controller(CvSpecialityController::class)->group(function () {
                    Route::get('/',  'index');
                    Route::post('/', 'store');
                    Route::get('/suggestions', 'suggestion');
                    Route::put('/{id}/certificates', 'updateCertificate');
                    Route::put('/{id}',  'update');
                    Route::delete('/{id}', 'destroy');
                });
            });
        });

        Route::prefix('permissions')->group(function () {
            Route::controller(PermissionController::class)->group(function () {
                Route::get('/', 'getPermission');
            });
        });

        Route::prefix('departments')->group(function () {
            Route::controller(DepartmentController::class)->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::get('/', 'index');
                Route::post('/', 'create');
            });
        });


        Route::prefix('levels')->group(function () {
            Route::controller(LevelController::class)->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::delete('/{id}', 'destroy');
                Route::get('/', 'index');
                Route::post('/', 'create');
            });
        });


        Route::prefix('positions')->group(function () {
            Route::controller(PositionController::class)->group(function () {
                Route::get('/{id}', 'show');
                Route::put('/{id}', 'update');
                Route::get('/', 'index');
                Route::post('/', 'store');
                // Route::get('/structure-organization', 'show');
            });
        });


        Route::prefix('degrees')->group(function () {
            Route::controller(CvEducationController::class)->group(function () {
                Route::get('/', 'degreeList');
            });
        });

        Route::prefix('employees')->group(function () {
            Route::get('/{id}/resignations', [EmployeeResignationController::class, 'showResignationsByEmployee']);
            Route::get('/{id}/payslips', [PayslipController::class, 'showPayslipByEmployee']);
            Route::get('/{id}/employee-bank-accounts', [EmployeeBankAccountController::class, 'showByEmployeeId']);
            Route::controller(EmploymentTypeController::class)->group(function () {
                Route::get('/types', 'index');
            });
            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/{id}/attendances', 'getAttendancesByEmployee');
            });
            Route::controller(EmployeeController::class)->group(function () {
                Route::get('/{id}', 'show');
                Route::group(['middleware' => ['permission:manage-employee']], function () {
                    Route::get('/', 'index');
                    Route::group(['middleware' => ['permission:manage-candidate']], function () {
                        Route::post('/', 'store');
                        Route::get('/{id}/salaries', 'showSalaryOnly');
                        Route::PATCH('/{id}/salaries', 'updateSalary');
                        Route::PUT('/{id}', 'update');
                        Route::delete('/{id}', 'destroy');
                    });
                    Route::get('/', 'index');
                });
            });
        });

        Route::prefix('attendances')->group(function () {
            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/company-employees', 'getAttendancesByCompany');
                Route::post('/', 'store');
            });
        });

        Route::prefix('attendance-securities')->group(function () {
            Route::controller(AttendanceController::class)->group(function () {
                Route::patch('/', 'validationBySecurity');
            });
        });


        Route::prefix('attendance-types')->group(function () {
            Route::controller(AttendanceController::class)->group(function () {
                Route::get('/', 'indexAttendanceType');
            });
        });

        // Route::prefix('shifts')->group(function () {
        //     Route::controller(ShiftController::class)->group(function () {
        //         Route::get('/', 'index');
        //     });
        // });

        Route::group(['middleware' => ['permission:manage-employee']], function () {
            Route::prefix('/salary-types')->group(function () {
                Route::controller(SalaryTypeController::class)->group(function () {
                    Route::get('/{id}', 'index');
                    Route::get('/', 'index');
                    Route::post('/', 'store');
                    Route::put('/{id}', 'update');
                });
            });
        });

        Route::prefix('leave-permissions')->group(function () {
            Route::controller(LeavePermissionController::class)->group(function () {
                Route::get('/{id}', 'show');
                Route::get('/{companyId}/companies', 'indexForCompany');
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::put('/{id}/status', 'updateLeavePermissionStatus');
                Route::put('/{id}', 'update');
                // Route::apiResource('/', LeavePermissionController::class);
            });
        });
        Route::apiResource('leave-permission-occasions', LeavePermissionOccasionController::class);

        Route::apiResource('candidate-positions', CandidatePositionController::class)->only(['index', 'show', 'store', 'update']);
        Route::prefix('candidate-positions')->controller(CandidatePositionController::class)->group(function () {
            Route::put('/{id}/verified', 'verified');
            Route::delete('/{id}/verified', 'unverified');
        });

        Route::prefix('religions')->group(function () {
            Route::controller(ReligionController::class)->group(function () {
                Route::get('/', 'index');
                Route::post('/', 'index');
            });
        });

        Route::prefix('marriage-statuses')->group(function () {
            Route::controller(MarriageStatusController::class)->group(function () {
                Route::get('/', 'index');
            });
        });
    });

    Route::middleware('throttle:1000,60')->controller(BlastController::class)->group(function () {
        Route::post('blast-wa', 'blastWhatsApp');
        Route::post('blast', 'blast');
    });
});
