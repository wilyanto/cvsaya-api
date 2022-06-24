<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Services\CRMBlastLogService;
use App\Services\WhatsappService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyCandidateReminder extends Command
{
    private $CRMBlastLogService,
        $whatsappService;

    public function __construct(
        CRMBlastLogService $CRMBlastLogService,
        WhatsappService $whatsappService
    ) {
        parent::__construct();

        $this->CRMBlastLogService = $CRMBlastLogService;
        $this->whatsappService = $whatsappService;
    }

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidateReminder:daily';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remind Candidate to fill cvsaya if not completed yet';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $candidates = Candidate::where('id', 1)->get();
        // get from api keys table
        $key = "9697bbbf-27af-4aae-9621-8be2a540c741";
        $countryCode = "62";
        $phoneNumber = "82368355626";

        $batchMessages = [];
        foreach ($candidates as $candidate) {
            $message = "";
            $expectedJob = $candidate->job;
            $remindSalary = "";
            if (!$expectedJob->expected_salary) {
                $remindSalary = "Belum Mengisi Gaji Yang Di Inginkan";
            }

            $experienceCount = $candidate->experiences->count();
            $remindExperience = "";
            if ($experienceCount <= 1) {
                $remindExperience = "Menambah Pengalaman";
            }

            $educationCount = $candidate->educations->count();
            $remindEducation = "";
            if ($educationCount <= 1) {
                $remindEducation = "Menambah Pendidikan";
            }

            $domicile = $candidate->domicile;
            $remindDomicile = "";
            if (
                $domicile->province_id == null ||
                $domicile->city_id == null ||
                $domicile->subdistrict_id == null
            ) {
                $remindDomicile = "Menambah Alamat Tempat Tinggal";
            }

            $message = "Reminder {$remindSalary}, {$remindExperience}, {$remindEducation}, {$remindDomicile}";

            $batchMessages[] = [
                "key" => $key,
                "country_code" => $candidate->country_code,
                "phone_number" => $candidate->phone_number,
                "message" => $message
            ];
            $this->whatsappService->sendMessage($batchMessages);
            $this->CRMBlastLogService->create($candidate);
        }
    }
}
