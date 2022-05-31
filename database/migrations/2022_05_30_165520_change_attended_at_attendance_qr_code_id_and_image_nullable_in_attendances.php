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
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('attended_at')->nullable()->change();
            $table->uuid('attendance_qr_code_id')->nullable()->change();
            $table->uuid('image')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->timestamp('attended_at')->nullable(false)->change();
            $table->uuid('attendance_qr_code_id')->nullable(false)->change();
            $table->uuid('image')->nullable(false)->change();
        });
    }
};
