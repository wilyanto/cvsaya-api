<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\CvProfileDetailObserver;
use App\Observers\CvDocumentationObserver;
use App\Models\CvDocumentation;
use App\Models\CvExperience;
use App\Models\CvProfileDetail;
use App\Observers\CvExperienceObserver;
use App\Models\CvEducation;
use App\Observers\CvEducationObserver;
use App\Models\CvCertification;
use App\Observers\CvCertificationObserver;
use App\Models\CvHobby;
use App\Observers\CvHobbyObserver;
use App\Models\CvSosmed;
use App\Observers\CvSosmedObserver;
use App\Models\CvSpeciality;
use App\Observers\CvSpecialityObserver;
use App\Models\CvSpecialityCertificate;
use App\Models\CandidateEmployee;
use App\Observers\CvSpecialityCertificateObserver;
use App\Observers\CandidateEmployeeObserver;
use App\Observers\CvDomicileObserver;
use App\Models\CvDomicile;
use App\Models\CvExpectedJob;
use App\Observers\CvExpectedJobObserver;
use App\Models\CandidateEmployeeSchedule;
use App\Observers\CandidateEmployeeScheduleObserver;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        CvProfileDetail::observe(CvProfileDetailObserver::class);
        CvSosmed::observe(CvSosmedObserver::class);
        CvDomicile::observe(CvDomicileObserver::class);
        CvDocumentation::observe(CvDocumentationObserver::class);
        CvExpectedJob::observe(CvExpectedJobObserver::class);
        CvExperience::observe(CvExperienceObserver::class);
        CvEducation::observe(CvEducationObserver::class);
        CvCertification::observe(CvCertificationObserver::class);
        CvSpeciality::observe(CvSpecialityObserver::class);
        CvSpecialityCertificate::observe(CvSpecialityCertificateObserver::class);
        CvHobby::observe(CvHobbyObserver::class);


        CandidateEmployee::observe(CandidateEmployeeObserver::class);
        CandidateEmployeeSchedule::observe(CandidateEmployeeScheduleObserver::class);
    }
}
