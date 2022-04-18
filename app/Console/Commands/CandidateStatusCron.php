<?php

namespace App\Console\Commands;

use App\Models\Candidate;
use App\Http\Controllers\Api\v1\CvProfileDetailController;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CandidateStatusCron extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'candidate:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Corn Job status candidate';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $candidates = Candidate::where('status', 3)->get();
        $totalBlast = 0;
        foreach ($candidates as $candidate) {
            if (strtotime($this->created_at . '+3 week') <= strtotime("now")) {
                try {
                    $profileDetailController = new CvProfileDetailController;
                    $profileStatus = $profileDetailController->getStatus($candidate->user_id);
                    $original = $profileStatus->original;
                    $status = $original['data']['completeness_status'];
                    if (
                        $status['is_profile_completed'] == false ||
                        $status['is_job_completed'] == false ||
                        $status['is_document_completed']  == false ||
                        $status['is_cv_completed'] == false
                    ) {
                        $totalBlast++;
                    }
                } catch (Exception $e) {
                    Log::info($e->getMessage());
                    Log::info('user-id : ' . $candidate->user_id);
                }
            }
        }
        Log::info($totalBlast);
        $this->info($totalBlast);
    }
}
