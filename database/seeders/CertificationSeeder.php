<?php

namespace Database\Seeders;

use App\Models\Candidate;
use App\Models\CvCertification;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CertificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $prodCvCertifications = DB::connection('existing-cvsaya')->table('cv_certifications')
            ->get();

        $newCertifications = [];
        $candidates = DB::table('candidates')->get();
        Log::info(count($prodCvCertifications));

        foreach ($prodCvCertifications as $prodCvCertification) {
            Log::info($prodCvCertification->user_id);
            $candidate = $candidates->where('user_id', $prodCvCertification->user_id)
                ->first();

            if ($candidate) {
                $prodCvCertification->candidate_id = $candidate->id;
                unset($prodCvCertification->user_id);
                array_push($newCertifications, [
                    'candidate_id' => $candidate->id,
                    'name' => $prodCvCertification->name,
                    'organization' => $prodCvCertification->organization,
                    'issued_at' => $prodCvCertification->issued_at,
                    'expired_at' => $prodCvCertification->expired_at,
                    'credential_id' => $prodCvCertification->credential_id,
                    'credential_url' => $prodCvCertification->credential_url,
                    'created_at' => $prodCvCertification->created_at,
                    'updated_at' => $prodCvCertification->updated_at,
                    'deleted_at' => $prodCvCertification->deleted_at,
                ]);
            }
        }

        DB::transaction(function () use (
            $newCertifications,
        ) {
            $chunckedCertifications = array_chunk($newCertifications, 1000);
            foreach ($chunckedCertifications as $certifications) {
                CvCertification::insert($certifications);
            }
        });
    }
}
