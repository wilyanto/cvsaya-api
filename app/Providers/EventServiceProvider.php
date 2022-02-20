<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\UserProfileDetailObserver;
use App\Models\CvExperiences;
use App\Models\CvProfileDetail;
use App\Observers\ExperiencesObserver;
use App\Models\CvEducations;
use App\Observers\EducationObserver;
use App\Models\CvCertifications;
use App\Observers\CertificationsObserver;
use App\Models\CvHobbies;
use App\Observers\HobbiesObserver;
use App\Models\CvSosmeds;
use App\Observers\SosmedObserver;
use App\Models\CvSpecialities;
use App\Observers\SpecialitiesObserver;
use App\Models\CvSpecialityCertificates;
use App\Models\CandidateEmployees;
use App\Observers\SpecialityCertificatesObserver;
use App\Observers\CandidateEmpolyessObserver;

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
        CvProfileDetail::observe(UserProfileDetailObserver::class);
        CvExperiences::observe(ExperiencesObserver::class);
        CvEducations::observe(EducationObserver::class);
        CvCertifications::observe(CertificationsObserver::class);
        CvHobbies::observe(HobbiesObserver::class);
        CvSosmeds::observe(SosmedObserver::class);
        CvSpecialities::observe(SpecialitiesObserver::class);
        CvSpecialityCertificates::observe(SpecialityCertificatesObserver::class);
        CandidateEmployees::observe(CandidateEmpolyessObserver::class);
    }
}
