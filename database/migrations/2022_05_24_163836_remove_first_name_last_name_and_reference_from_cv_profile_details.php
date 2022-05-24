<?php

use App\Models\Candidate;
use App\Models\CvProfileDetail;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cv_profile_details', function (Blueprint $table) {
            $profileDetails = CvProfileDetail::get();
            foreach ($profileDetails as $profileDetail) {
                Candidate::where('id', $profileDetail->candidate_id)->update([
                    'name' => $profileDetail->first_name . ' ' . $profileDetail->last_name,
                    'reference' => $profileDetail->reference
                ]);
            }
            $table->dropColumn(['first_name', 'last_name', 'reference']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_profile_details', function (Blueprint $table) {
            //
        });
    }
};
