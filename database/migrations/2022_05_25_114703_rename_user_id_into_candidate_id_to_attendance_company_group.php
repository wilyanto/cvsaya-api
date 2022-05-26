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
        Schema::table('attendance_company_group', function (Blueprint $table) {
            $table->renameColumn('user_id', 'candidate_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendance_company_group', function (Blueprint $table) {
            $table->renameColumn('candidate_id', 'user_id');
        });
    }
};
