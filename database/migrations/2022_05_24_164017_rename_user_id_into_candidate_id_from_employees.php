<?php

use App\Models\Candidate;
use App\Models\Employee;
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
        Schema::table('employees', function (Blueprint $table) {
            $candidates = Candidate::get();
            foreach ($candidates as $candidate) {
                $employees = Employee::where('user_id', $candidate->user_id)->get();
                foreach ($employees as $employee) {
                    $candidate->update(['user_id' => $employee->user_id]);
                    $employee->update(['user_id' => $candidate->id]);
                }
            }
            $table->renameColumn('user_id', 'candidate_id');
            $table->foreign('candidate_id')->references('id')->on('candidates');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->dropForeign(['candidate_id']);

            $table->renameColumn('candidate_id', 'user_id');
        });
    }
};
