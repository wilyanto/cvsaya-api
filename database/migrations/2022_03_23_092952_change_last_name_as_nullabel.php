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
        Schema::table('cv_profile_details', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
        });

        Schema::table('cv_log_profile_details', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
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
            $table->string('last_name')->nullable(false)->change();
        });

        Schema::table('cv_log_profile_details', function (Blueprint $table) {
            $table->string('last_name')->nullable()->change();
        });
    }
};
