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
        Schema::table('cv_educations', function (Blueprint $table) {
            $table->renameColumn('school','instance');
        });

        Schema::table('cv_log_educations', function (Blueprint $table) {
            $table->renameColumn('school','instance');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cv_educations', function (Blueprint $table) {
            $table->renameColumn('instance','school');
        });

        Schema::table('cv_log_educations', function (Blueprint $table) {
            $table->renameColumn('instance','school');
        });
    }
};
