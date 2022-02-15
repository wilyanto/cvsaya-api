<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use App\Observers\UserProfileDetailObserver;
use App\Models\Experiences;
use App\Models\UserProfileDetail;
use App\Observers\ExperiencesObserver;
use App\Models\Educations;
use App\Observers\EducationObserver;
use App\Models\Certifications;
use App\Observers\CertificationsObserver;
use App\Models\Hobbies;
use App\Observers\HobbiesObserver;
use App\Models\Sosmeds;
use App\Observers\SosmedObserver;
use App\Models\Specialities;
use App\Observers\SpecialitiesObserver;
use App\Models\SpecialityCertificates;
use App\Observers\SpecialityCertificatesObserver;

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
        UserProfileDetail::observe(UserProfileDetailObserver::class);
        Experiences::observe(ExperiencesObserver::class);
        Educations::observe(EducationObserver::class);
        Certifications::observe(CertificationsObserver::class);
        Hobbies::observe(HobbiesObserver::class);
        Sosmeds::observe(SosmedObserver::class);
        Specialities::observe(SpecialitiesObserver::class);
        SpecialityCertificates::observe(SpecialityCertificatesObserver::class);
    }
}
