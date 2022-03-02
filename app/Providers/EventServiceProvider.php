<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\CvProfileDetailObserver;
use App\Observers\CvDocumentationsObserver;
use App\Models\CvDocumentations;
use App\Models\CvExperiences;
use App\Models\CvProfileDetail;
use App\Observers\CvExperiencesObserver;
use App\Models\CvEducations;
use App\Observers\CvEducationObserver;
use App\Models\CvCertifications;
use App\Observers\CvCertificationsObserver;
use App\Models\CvHobbies;
use App\Observers\CvHobbiesObserver;
use App\Models\CvSosmeds;
use App\Observers\CvSosmedObserver;
use App\Models\CvSpecialities;
use App\Observers\CvSpecialitiesObserver;
use App\Models\CvSpecialityCertificates;
use App\Models\CandidateEmployees;
use App\Observers\CvSpecialityCertificatesObserver;
use App\Observers\CandidateEmpolyessObserver;
use App\Observers\CvAddressObserver;
use App\Models\CvAddress;
use App\Models\CvExpectedPositions;
use App\Observers\CvExpectedPositionsObserver;
use App\Models\CandidateEmpolyeeSchedule;
use App\Observers\CandidateEmpolyeeScheduleObserver;

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
        CvSosmeds::observe(CvSosmedObserver::class);
        CvAddress::observe(CvAddressObserver::class);
        CvDocumentations::observe(CvDocumentationsObserver::class);
        CvExpectedPositions::observe(CvExpectedPositionsObserver::class);
        CvExperiences::observe(CvExperiencesObserver::class);
        CvEducations::observe(CvEducationObserver::class);
        CvCertifications::observe(CvCertificationsObserver::class);
        CvSpecialities::observe(CvSpecialitiesObserver::class);
        CvSpecialityCertificates::observe(CvSpecialityCertificatesObserver::class);
        CvHobbies::observe(CvHobbiesObserver::class);


        CandidateEmployees::observe(CandidateEmpolyessObserver::class);
        CandidateEmpolyeeSchedule::observe(CandidateEmpolyeeScheduleObserver::class);
    }
}
