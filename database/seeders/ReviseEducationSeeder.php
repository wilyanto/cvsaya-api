<?php

namespace Database\Seeders;

use App\Models\CvEducation;
use Exception;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ReviseEducationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $newCvEducations = DB::connection('existing-cvsaya')->table('cv_educations')
            ->where('field_of_study', '!=', '-')->get();

        $existingCvEducations = DB::table('cv_educations')->where('field_of_study', '!=', '-')->get();

        try {
            DB::transaction(function () use ($newCvEducations, $existingCvEducations) {
                foreach ($existingCvEducations as $existingCvEducation) {
                    Log::info(json_encode($existingCvEducation));
                    $educationFound = $newCvEducations
                        ->where('grade', $existingCvEducation->grade)
                        ->where('instance', $existingCvEducation->instance)
                        ->where('started_at', $existingCvEducation->started_at)
                        ->first();

                    if ($educationFound) {
                        CvEducation::find($existingCvEducation->id)->update([
                            'field_of_study' => $educationFound->field_of_study,
                        ]);
                        // $educationFound->update([
                        //     'field_of_study' => $educationFound->field_of_study,
                        // ]);
                    }
                }
            });
        } catch (Exception $e) {
            Log::info('Error :' . $e);
        }
    }
}
