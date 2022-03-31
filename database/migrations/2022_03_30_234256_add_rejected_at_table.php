<?php

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
        Schema::table('candidate_interview_schedules',function(Blueprint $table){
            $table->timestamp('rejected_at')->nullable()->after('note');
        });

        Schema::table('candidate_log_interview_schedules',function(Blueprint $table){
            $table->timestamp('rejected_at')->nullable()->after('note');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('candidate_log_interview_schedules',function(Blueprint $table){
            $table->dropColumn('rejected_at');
        });

        Schema::table('candidate_interview_schedules',function(Blueprint $table){
            $table->dropColumn('rejected_at');
        });
    }
};
