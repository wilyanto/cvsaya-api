<?php

namespace App\Console\Commands;

use App\Enums\BlastLogStatusEnum;
use App\Enums\BlastTypeEnum;
use App\Models\BlastType;
use App\Models\Candidate;
use App\Models\CRMCredential;
use App\Services\CRMBlastLogService;
use App\Services\MessageService;
use App\Services\WhatsappService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class DailyCandidateReminder extends Command
{
    private $CRMBlastLogService,
        $messageService,
        $whatsappService;

    public function __construct(
        CRMBlastLogService $CRMBlastLogService,
        MessageService $messageService,
        WhatsappService $whatsappService
    ) {
        parent::__construct();

        $this->CRMBlastLogService = $CRMBlastLogService;
        $this->messageService = $messageService;
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
        $candidates = Candidate::get();
        $blastType = BlastType::where('name', BlastTypeEnum::interviewReminder())->firstOrFail();
        $blastTypeRules = $blastType->blastTypeRules;
        $credential = CRMcredential::firstOrFail();
        // set jadwal / jam format hour
        // consider limit per number
        $credentialKey = $credential->key;
        foreach ($candidates as $candidate) {
            $blastLogs = $candidate->blastLogs()
                ->where('credential_id', $credential->id)
                ->latest()
                ->get();

            if ($blastTypeRules->count() === 0) {
                continue;
            }

            $blastLogCount = $blastLogs->count();
            $blastTypeRule = $blastTypeRules->where('count', '<=', $blastLogCount)
                ->sortByDesc('count')
                ->first();

            if (
                $blastLogCount !== 0 &&
                $blastLogs->first()->created_at->diffInDays(now()) <= $blastTypeRule->duration
            ) {
                continue;
            }

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

            $paramValue = [
                'remindSalary' => $remindSalary,
                'remindExperience' => $remindExperience,
                'remindEducation' => $remindEducation,
                'remindDomicile' => $remindDomicile
            ];

            $shouldBeReminded = false;
            foreach ($paramValue as $key => $value) {
                if (!empty($value)) {
                    $shouldBeReminded = true;
                    break;
                }
            }

            if (!$shouldBeReminded) {
                continue;
            }

            $messageParamValue = [
                'body' => $paramValue
            ];

            $template =  "Reminder {{remindSalary}}, {{remindExperience}}, {{remindEducation}}, {{remindDomicile}}";
            $messageTemplate = [
                'body' => $template
            ];
            $message = $this->messageService->constructMessage($template, $paramValue);

            $message = [
                "key" => $credentialKey,
                "country_code" => $candidate->country_code,
                "phone_number" => $candidate->phone_number,
                "message" => $message
            ];
            $response = $this->whatsappService->sendMessage($message);
            $uuid = null;
            $status = BlastLogStatusEnum::pending();
            if ($response->isSuccessful()) {
                $responseJson = json_decode($response->content(), true);
                $uuid = $responseJson['uuid'];
                $status = BlastLogStatusEnum::sent();
            } else {
                $uuid = null;
                $status = BlastLogStatusEnum::failed();
            }

            $CRMBlastLog = $this->CRMBlastLogService->create($candidate, $credential, $blastType, $messageParamValue, $messageTemplate);
            $this->CRMBlastLogService->updateStatusAndUuid($CRMBlastLog->id, $status, $uuid);
        }
    }
}
